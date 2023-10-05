<?php

declare(strict_types=1);

namespace src\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Spiral\Attributes\Factory;
use Spiral\DatabaseSeeder\Attribute\Seeder;

final class SeederTest extends TestCase
{
    public function testSeeder(): void
    {
        $reader = (new Factory())->create();

        $this->assertNull(
            $reader->firstClassMetadata(new \ReflectionClass(new class(){}), Seeder::class)
        );
        $this->assertEquals(
            new Seeder(),
            $reader->firstClassMetadata(new \ReflectionClass(new #[Seeder] class(){}), Seeder::class)
        );
        $this->assertEquals(
            new Seeder(priority: 5),
            $reader->firstClassMetadata(new \ReflectionClass(new #[Seeder(priority: 5)] class(){}), Seeder::class)
        );
    }
}
