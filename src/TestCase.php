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

    private function setUpTraits()
    {
        if (\method_exists($this, 'refreshDatabase')) {
            $this->refreshDatabase();
        }
    }
}
