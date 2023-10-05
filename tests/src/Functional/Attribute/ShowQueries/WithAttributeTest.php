<?php

declare(strict_types=1);

namespace Tests\Functional\Attribute\ShowQueries;

use Spiral\DatabaseSeeder\Attribute\ShowQueries as Attribute;
use Spiral\DatabaseSeeder\Database\Logger\StdoutLogger;
use Spiral\DatabaseSeeder\Database\Traits\ShowQueries;
use Spiral\Logger\NullLogger;
use Tests\Functional\TestCase;

final class WithAttributeTest extends TestCase
{
    use ShowQueries;

    #[Attribute]
    public function testWithAttribute():void
    {
        $driver = $this->getDriver();

        $this->assertInstanceOf(
            StdoutLogger::class,
            (new \ReflectionProperty($driver, 'logger'))->getValue($driver)
        );
    }

    public function testWithoutAttribute():void
    {
        $driver = $this->getDriver();

        $this->assertInstanceOf(
            NullLogger::class,
            (new \ReflectionProperty($driver, 'logger'))->getValue($driver)
        );
    }
}
