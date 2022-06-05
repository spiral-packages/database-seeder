<?php

declare(strict_types=1);

namespace Tests\Functional;

use Spiral\Boot\Bootloader\ConfigurationBootloader;
use Spiral\Cycle\Bootloader\AnnotatedBootloader;
use Spiral\Cycle\Bootloader\CycleOrmBootloader;
use Spiral\Cycle\Bootloader\DatabaseBootloader;
use Spiral\Cycle\Bootloader\MigrationsBootloader;
use Spiral\Cycle\Bootloader\SchemaBootloader;
use Spiral\DatabaseSeeder\Bootloader\DatabaseSeederBootloader;

abstract class TestCase extends \Spiral\DatabaseSeeder\TestCase
{
    public function rootDirectory(): string
    {
        return \dirname(__DIR__, 2);
    }

    public function defineDirectories(string $root): array
    {
        return [
            'root' => $root,
            'app' => $root . '/app',
            'runtime' => $root . '/app/runtime',
            'cache' => $root . '/app/runtime/cache',
            'config' => $root . '/app/config',
        ];
    }

    public function defineBootloaders(): array
    {
        return [
            ConfigurationBootloader::class,
            DatabaseBootloader::class,
            MigrationsBootloader::class,
            SchemaBootloader::class,
            CycleOrmBootloader::class,
            AnnotatedBootloader::class,
            DatabaseSeederBootloader::class,
        ];
    }
}
