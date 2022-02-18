<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Tests;

use Spiral\Boot\Bootloader\ConfigurationBootloader;
use Spiral\DatabaseSeeder\Bootloader\DatabaseSeederBootloader;

class TestCase extends \Spiral\Testing\TestCase
{
    public function rootDirectory(): string
    {
        return __DIR__.'/../';
    }

    public function defineBootloaders(): array
    {
        return [
            ConfigurationBootloader::class,
            DatabaseSeederBootloader::class,
            // ...
        ];
    }
}
