<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\Migrations\Config\MigrationConfig;
use Spiral\DatabaseSeeder\Attribute\RefreshDatabase as RefreshDatabaseAttribute;
use Cycle\Database\Config\SQLite\MemoryConnectionConfig;
use Cycle\Database\Database;
use Cycle\Database\DatabaseManager;
use Cycle\Database\DatabaseProviderInterface;
use Spiral\Boot\FinalizerInterface;
use Spiral\DatabaseSeeder\Database\Cleaner;
use Spiral\DatabaseSeeder\Database\TestStrategy\RefreshStrategy;

trait RefreshDatabase
{
    private ?RefreshStrategy $refreshStrategy = null;

    /**
     * Refresh database after each test.
     */
    public function refreshDatabase(): void
    {
        $this->beforeRefreshingDatabase();

        $this->getRefreshStrategy()->refresh();

        $this->afterRefreshingDatabase();
    }

    protected function tearDownRefreshDatabase(): void
    {
        if (!$this->getRefreshStrategy()->isRefreshAttributeEnabled()) {
            $this->refreshDatabase();
            return;
        }

        $attributes = $this->getTestAttributes(RefreshDatabaseAttribute::class);
        if ($attributes === []) {
            return;
        }

        $this->getRefreshStrategy()->setDatabase($attributes[0]->database);
        $this->getRefreshStrategy()->setExcept(
            \array_merge($attributes[0]->except, [$this->getConfig(MigrationConfig::CONFIG)['table']])
        );

        $this->refreshDatabase();
    }

    protected function getRefreshStrategy(): RefreshStrategy
    {
        if ($this->refreshStrategy === null) {
            $this->refreshStrategy = new RefreshStrategy(
                cleaner: new Cleaner($this->getContainer()->get(DatabaseProviderInterface::class)),
                useAttribute: false,
                except: [$this->getConfig(MigrationConfig::CONFIG)['table']]
            );
        }

        return $this->refreshStrategy;
    }

    /**
     * Perform any work that should take place before the database has started refreshing.
     */
    protected function beforeRefreshingDatabase(): void
    {
        // ...
    }

    /**
     * Perform any work that should take place once the database has finished refreshing.
     */
    protected function afterRefreshingDatabase(): void
    {
        // ...
    }

    /**
     * @deprecated
     */
    public function beginDatabaseTransaction(): void
    {
        $driver = $this->getContainer()->get(Database::class)->getDriver();
        $driver->beginTransaction();

        $this->getContainer()->get(FinalizerInterface::class)->addFinalizer(static function () use($driver) {
            while ($driver->getTransactionLevel() >= 1) {
                $driver->rollbackTransaction();
            }
            $driver->disconnect();
        });
    }

    /**
     * @deprecated
     */
    protected function refreshInMemoryDatabase(): void
    {
        $this->getRefreshStrategy()->refresh();
    }

    /**
     * @deprecated
     */
    protected function refreshTestDatabase(): void
    {
        $this->getRefreshStrategy()->refresh();
    }

    /**
     * @deprecated
     */
    protected function usingInMemoryDatabase(): bool
    {
        $manager = $this->getContainer()->get(DatabaseManager::class);
        $info = $manager->database()->getDriver()->__debugInfo();

        return isset($info['connection']) && $info['connection'] instanceof MemoryConnectionConfig;
    }
}
