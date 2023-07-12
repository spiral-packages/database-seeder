<?php

declare(strict_types=1);

namespace Tests\Unit\Seeder;

use PHPUnit\Framework\TestCase;
use Spiral\DatabaseSeeder\Seeder\Locator;
use Spiral\DatabaseSeeder\Seeder\LocatorInterface;
use Tests\Database\Seeder\CommentSeeder;
use Tests\Database\Seeder\PostSeeder;
use Tests\Database\Seeder\UserSeeder;

final class LocatorTest extends TestCase
{
    public function testFindSeederByName(): void
    {
        $locator = new Locator(
            $seedersLocator = \Mockery::mock(LocatorInterface::class),
        );

        $seedersLocator->shouldReceive('find')->once()->andReturn([
            $seeder = new CommentSeeder(),
            new PostSeeder(),
            new UserSeeder(),
        ]);

        $seeders = $locator->findSeeders('CommentSeeder');

        $this->assertEquals([$seeder], $seeders);
    }


    public function testFindAllSeeders(): void
    {
        $locator = new Locator(
            $seedersLocator = \Mockery::mock(LocatorInterface::class),
            $directoriesSeedersLocator = \Mockery::mock(LocatorInterface::class),
        );

        $seedersLocator->shouldReceive('find')
            ->once()
            ->andReturn([
                PostSeeder::class => new PostSeeder(),
                UserSeeder::class => $userSeeder = new UserSeeder(),
            ]);

        $directoriesSeedersLocator->shouldReceive('find')
            ->once()
            ->andReturn([
                PostSeeder::class => $postSeeder = new PostSeeder(),
                CommentSeeder::class => $commentSeeder = new CommentSeeder()
            ]);

        $seeders = $locator->findSeeders();

        $this->assertEquals([
            $postSeeder,
            $commentSeeder,
            $userSeeder,
        ], $seeders);
    }
}
