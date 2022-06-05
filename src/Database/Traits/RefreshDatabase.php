<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\Database\Config\SQLite\MemoryConnectionConfig;
use Cycle\Database\DatabaseManager;
use Spiral\DatabaseSeeder\Database\Exception\RefreshDatabaseException;

trait RefreshDatabase
{
    /**
     * Define hooks to migrate the database before and after each test.
     */
    public function refreshDatabase(): void
    {
        $this->beforeRefreshingDatabase();

        $this->usingInMemoryDatabase()
            ? $this->refreshInMemoryDatabase()
            : $this->refreshTestDatabase();

        $this->afterRefreshingDatabase();
    }

    /**
     * Refresh the in-memory database.
     */
    protected function refreshInMemoryDatabase(): void
    {
        $config = $this->getConfig('migration');
        if (empty($config['directory'])) {
            throw new RefreshDatabaseException(
                'Please, configure migrations in your test application to use auto database refreshing.'
            );
        }

        if (!isset($config['safe']) || $config['safe'] !== true) {
            throw new RefreshDatabaseException(
                'The `safe` parameter in the test application migrations configuration must be set to true.'
            );
        }

        $this->getConsole()->run('cycle:migrate', ['--run' => true]);

        $this->cleanupDirectories($config['directory']);
    }

    /**
     * Refresh a conventional test database.
     */
    protected function refreshTestDatabase(): void
    {

    }

    /**
     * Determine if an in-memory database is being used.
     */
    protected function usingInMemoryDatabase(): bool
    {
        $manager = $this->getContainer()->get(DatabaseManager::class);
        $info = $manager->database()->getDriver()->__debugInfo();

        return isset($info['connection']) && $info['connection'] instanceof MemoryConnectionConfig;
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
}
