<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder;

abstract class TestCase extends \Spiral\Testing\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpTraits();
    }

    private function setUpTraits(): void
    {
        /** @see \Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase */
        if (\method_exists($this, 'refreshDatabase')) {
            $this->refreshDatabase();
        }

        /** @see \Spiral\DatabaseSeeder\Database\Traits\DatabaseMigrations */
        if (\method_exists($this, 'runDatabaseMigrations')) {
            $this->runDatabaseMigrations();
        }
    }
}
