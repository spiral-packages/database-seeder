<?php

declare(strict_types=1);

namespace src\Unit\Attribute;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Spiral\Attributes\Factory;
use Spiral\DatabaseSeeder\Attribute\ShowQueries;

final class ShowQueriesTest extends TestCase
{
    #[DataProvider(methodName: 'attributesProvider')]
    public function testShowQueries(string $method, mixed $expected): void
    {
        $reader = (new Factory())->create();

        $this->assertEquals(
            $expected,
            $reader->firstFunctionMetadata(new \ReflectionMethod($this, $method), ShowQueries::class)
        );
    }

    public static function attributesProvider(): \Traversable
    {
        yield ['withoutAttribute', null];
        yield ['withAttribute', new ShowQueries()];
    }

    public function withoutAttribute():void
    {
    }

    #[ShowQueries]
    public function withAttribute():void
    {
    }
}
