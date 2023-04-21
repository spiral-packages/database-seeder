<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\Common\Database\Traits;

use Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase;
use Tests\Functional\TestCase;

abstract class RefreshDatabaseTestCase extends TestCase
{
    use RefreshDatabase;

    // disabling auto executing DB traits
    protected function setUp(): void
    {
        $this->initApp(static::ENV);
    }
}
