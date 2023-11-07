<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Strategy;

use Cycle\Database\DatabaseInterface;
use Spiral\Testing\TestCase;

/**
 * This strategy utilizes MigrationStrategy without executing migration and rolling back migration for each test
 * but performs migration prior to running the first test. It wraps the test execution in a transaction before each
 * test.
 */
class TransactionStrategy
{
    protected MigrationStrategy $migrationStrategy;

    public function __construct(
        protected TestCase $testCase,
        ?MigrationStrategy $migrationStrategy = null
    ) {
        $this->migrationStrategy = $migrationStrategy ?? new MigrationStrategy($this->testCase);
    }

    public function begin(): void
    {
        $this->migrationStrategy->migrate();

        if ($this->migrationStrategy->isCreateMigrations()) {
            $this->migrationStrategy->deleteMigrations();
        }

        $this->testCase->getContainer()->get(DatabaseInterface::class)->getDriver()->beginTransaction();
    }

    public function rollback(): void
    {
        $this->testCase->getContainer()->get(DatabaseInterface::class)->getDriver()->rollbackTransaction();
    }
}
