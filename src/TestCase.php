<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder;

use Spiral\Core\ContainerScope;
use Spiral\DatabaseSeeder\Database\Traits\DatabaseAsserts;

abstract class TestCase extends \Spiral\Testing\TestCase
{
    use DatabaseAsserts;

    protected function setUp(): void
    {
        parent::setUp();

        // Bind container to ContainerScope
        (new \ReflectionClass(ContainerScope::class))->setStaticPropertyValue('container', $this->getContainer());

        $this->setUpTraits();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        (new \ReflectionClass(ContainerScope::class))->setStaticPropertyValue('container', null);
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
