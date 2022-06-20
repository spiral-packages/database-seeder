<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Spiral\Boot\FinalizerInterface;
use Spiral\DatabaseSeeder\Database\DatabaseState;
use Spiral\DatabaseSeeder\Database\Exception\DatabaseMigrationsException;

trait DatabaseMigrations
{
    /**
     * Migrate the database before and after each test.
     */
    public function runDatabaseMigrations(): void
    {
        $this->runCommand('cycle:migrate', ['--run' => true]);

        /* TODO wait resolving bug with finalizers
        $directory = $this->getMigrationsDirectory();
        $self = $this;
        $this->getContainer()->get(FinalizerInterface::class)->addFinalizer(static function () use($self, $directory) {
            $self->runCommand('migrate:rollback', ['--all' => true]);

            $self->cleanupDirectories($directory);

            DatabaseState::$migrated = false;
        });
        */
    }

    public function runDatabaseRollback(): void
    {
        $directory = $this->getMigrationsDirectory();

        $this->runCommand('migrate:rollback', ['--all' => true]);

        $this->cleanupDirectories($directory);

        DatabaseState::$migrated = false;
    }

    /**
     * @invisible
     * @internal
     */
    private function getMigrationsDirectory(): string
    {
        $config = $this->getConfig('migration');
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
