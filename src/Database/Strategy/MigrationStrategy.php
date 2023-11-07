<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Strategy;

use Spiral\DatabaseSeeder\Database\DatabaseState;
use Spiral\DatabaseSeeder\Database\Exception\DatabaseMigrationsException;
use Spiral\Files\FilesInterface;
use Spiral\Testing\TestCase;

/**
 * Use the `createMigration` parameter set to `false` if you want to use production application migrations.
 * No new migrations will be created and no migrations will be deleted.
 *
 * Use the `createMigration` parameter set to `true` if you want to use test application migrations.
 * Migrations will be created before the test is executed and deleted after execution.
 */
class MigrationStrategy
{
    public function __construct(
        protected TestCase $testCase,
        protected bool $createMigrations = false
    ) {
    }

    public function migrate(): void
    {
        if (!DatabaseState::$migrated) {
            $this->createMigrations
                ? $this->testCase->runCommand('cycle:migrate', ['--run' => true])
                : $this->testCase->runCommand('migrate', ['--force' => true]);
        }

        DatabaseState::$migrated = true;
    }

    public function rollback(): void
    {
        $this->testCase->runCommand('migrate:rollback', ['--all' => true]);

        if ($this->createMigrations) {
            $this->deleteMigrations();
        }

        DatabaseState::$migrated = false;
    }

    public function disableCreationMigrations(): void
    {
        $this->createMigrations = false;
    }

    public function enableCreationMigrations(): void
    {
        $this->createMigrations = true;
    }

    public function isCreateMigrations(): bool
    {
        return $this->createMigrations;
    }

    public function deleteMigrations(): void
    {
        $dir = $this->getMigrationsDirectory();
        $this->testCase->cleanupDirectories($dir);
        $this->testCase->getContainer()->get(FilesInterface::class)->ensureDirectory($dir, 0666);
    }

    protected function getMigrationsDirectory(): string
    {
        $config = $this->testCase->getConfig('migration');
        if (empty($config['directory'])) {
            throw new DatabaseMigrationsException(
                'Please, configure migrations in your test application to use DatabaseMigrations.'
            );
        }

        if (!isset($config['safe']) || $config['safe'] !== true) {
            throw new DatabaseMigrationsException(
                'The `safe` parameter in the test application migrations configuration must be set to true.'
            );
        }

        return $config['directory'];
    }
}
