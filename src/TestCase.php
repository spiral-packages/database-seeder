<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder;

use Spiral\DatabaseSeeder\Database\Traits\DatabaseAsserts;

/**
 * @deprecated Use traits instead
 */
abstract class TestCase extends \Spiral\Testing\TestCase
{
    use DatabaseAsserts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpTraits();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tearDownTraits();
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

    private function tearDownTraits(): void
    {
        /** @see \Spiral\DatabaseSeeder\Database\Traits\DatabaseMigrations */
        if (\method_exists($this, 'runDatabaseRollback')) {
            $this->runDatabaseRollback();
        }
    }
}
