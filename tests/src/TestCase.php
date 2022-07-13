<?php

declare(strict_types=1);

namespace Tests;

use Spiral\Boot\AbstractKernel;
use Spiral\Boot\Bootloader\ConfigurationBootloader;
use Spiral\DatabaseSeeder\Bootloader\DatabaseSeederBootloader;
use Spiral\Testing\TestableKernelInterface;
use Tests\App\TestApp;

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
        ];
    }
}
