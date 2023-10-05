<?php

declare(strict_types=1);

namespace Tests\Unit\Attribute;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Spiral\Attributes\Factory;
use Spiral\DatabaseSeeder\Attribute\RefreshDatabase;

final class RefreshDatabaseTest extends TestCase
{
    #[DataProvider(methodName: 'attributesProvider')]
    public function testRefreshDatabase(string $method, mixed $expected): void
    {
        $reader = (new Factory())->create();

        $this->assertEquals(
            $expected,
            $reader->firstFunctionMetadata(new \ReflectionMethod($this, $method), RefreshDatabase::class)
        );
    }

    public static function attributesProvider(): \Traversable
    {
        yield ['withoutAttribute', null];
        yield ['withAttribute', new RefreshDatabase()];
        yield ['withAttributeAndParameters', new RefreshDatabase(database: 'secondary', except: ['users'])];
    }

    public function withoutAttribute():void
    {
    }

    #[RefreshDatabase]
    public function withAttribute():void
    {
    }

    #[RefreshDatabase(database: 'secondary', except: ['users'])]
    public function withAttributeAndParameters():void
    {
    }
}
