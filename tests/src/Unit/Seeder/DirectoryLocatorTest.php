<?php

declare(strict_types=1);

namespace Tests\Unit\Seeder;

use PHPUnit\Framework\TestCase;
use Spiral\Core\FactoryInterface;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\DatabaseSeeder\Seeder\DirectoryLocator;
use Spiral\DatabaseSeeder\Seeder\SeederInterface;
use Spiral\Files\FilesInterface;
use Tests\Database\Seeder\UserSeeder;

final class DirectoryLocatorTest extends TestCase
{
    public function testFind(): void
    {
        $files = $this->createMock(FilesInterface::class);
        $files->method('isDirectory')->willReturn(true);
        $files->method('getFiles')->willReturn($this->getFiles());

        $locator = $this->createLocator($files);

        $seeders = $locator->find();

        // All seeders founded
        $this->assertCount(3, $seeders);

        $user = \array_values(
            \array_filter($seeders, static fn(SeederInterface $seeder) => $seeder instanceof UserSeeder)
        )[0];

        // check priority from seed class
        $this->assertSame(5, $user->getPriority());
    }

    public function testIsNotDir(): void
    {
        $files = $this->createMock(FilesInterface::class);
        $files->method('isDirectory')->willReturn(false);

        $locator = $this->createLocator($files);
        $seeders = $locator->find();

        $this->assertCount(0, $seeders);
    }

    private function createLocator(FilesInterface $files): DirectoryLocator
    {
        $config = new DatabaseSeederConfig([
            'seeders' => [
                'directory' => 'foo'
            ]
        ]);

        return new DirectoryLocator($files, $config,
            new class() implements FactoryInterface {
                public function make(string $alias, array $parameters = []): mixed
                {
                    return new $alias;
                }
            });
    }

    private function getFiles(): array
    {
        return [
            \dirname(__DIR__, 3) . '/app/database/Seeder/CommentSeeder.php',
            \dirname(__DIR__, 3) . '/app/database/Seeder/PostSeeder.php',
            \dirname(__DIR__, 3) . '/app/database/Seeder/UserSeeder.php',
            \dirname(__DIR__, 3) . '/app/database/Seeder/WrongSeeder.php',
        ];
    }
}
