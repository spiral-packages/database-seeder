<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\Database\Config\SQLite\MemoryConnectionConfig;
use Cycle\Database\DatabaseManager;

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
        $this->runCommand('cycle:sync');
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
