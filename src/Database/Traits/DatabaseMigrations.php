<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Spiral\DatabaseSeeder\Database\Strategy\MigrationStrategy;

trait DatabaseMigrations
{
    private ?MigrationStrategy $migrationStrategy = null;

    /**
     * Migrate the database before and after each test.
     */
    public function runDatabaseMigrations(): void
    {
        $this->beforeMigrateDatabase();

        $this->getMigrationStrategy()->migrate();

        $this->afterMigrateDatabase();
    }

    public function runDatabaseRollback(): void
    {
        $this->beforeRollbackDatabase();

        $this->getMigrationStrategy()->rollback();

        $this->afterRollbackDatabase();
    }

    protected function setUpDatabaseMigrations(): void
    {
        $this->runDatabaseMigrations();
    }

    protected function tearDownDatabaseMigrations(): void
    {
        $this->runDatabaseRollback();
    }

    protected function getMigrationStrategy(): MigrationStrategy
    {
        if ($this->migrationStrategy === null) {
            $this->migrationStrategy = new MigrationStrategy($this);
        }

        return $this->migrationStrategy;
    }

    /**
     * Perform any work that should take place before the database has started migrating.
     */
    protected function beforeMigrateDatabase(): void
    {
        // ...
    }

    /**
     * Perform any work that should take place once the database has finished migrating.
     */
    protected function afterMigrateDatabase(): void
    {
        // ...
    }

    /**
     * Perform any work that should take place before the database has started rollback.
     */
    protected function beforeRollbackDatabase(): void
    {
        // ...
    }

    /**
     * Perform any work that should take place once the database has finished rollback.
     */
    protected function afterRollbackDatabase(): void
    {
        // ...
    }
}
