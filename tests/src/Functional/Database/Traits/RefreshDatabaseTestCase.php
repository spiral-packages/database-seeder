<?php

declare(strict_types=1);

namespace Tests\Functional\Database\Traits;

use Spiral\DatabaseSeeder\Database\Strategy\MigrationStrategy;
use Spiral\DatabaseSeeder\Database\Traits\DatabaseMigrations;
use Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase;
use Spiral\DatabaseSeeder\Database\Traits\ShowQueries;
use Tests\Functional\TestCase;

final class RefreshDatabaseTestCase extends TestCase
{
    use RefreshDatabase, DatabaseMigrations, ShowQueries;

    protected function getMigrationStrategy(): MigrationStrategy
    {
        if ($this->migrationStrategy === null) {
            $this->migrationStrategy = new MigrationStrategy($this, true);
        }

        return $this->migrationStrategy;
    }
}
