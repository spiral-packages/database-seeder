<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Core\Container;
use Spiral\Config\ConfiguratorInterface;
use Spiral\DatabaseSeeder\Commands;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\Console\Bootloader\ConsoleBootloader;

class DatabaseSeederBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ConsoleBootloader::class
    ];

    public function __construct(
        private ConfiguratorInterface $config
    ) {
    }

    public function boot(Container $container, ConsoleBootloader $console): void
    {
        $this->initConfig();

        $console->addCommand(Commands\DatabaseSeederCommand::class);
    }

    public function start(Container $container): void
    {
    }

    private function initConfig(): void
    {
        $this->config->setDefaults(
            DatabaseSeederConfig::CONFIG,
            []
        );
    }
}
