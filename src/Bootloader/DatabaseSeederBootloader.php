<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Console\Bootloader\ConsoleBootloader;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\DatabaseSeeder\Console\Command\SeedCommand;

class DatabaseSeederBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ConsoleBootloader::class
    ];

    public function __construct(
        private ConfiguratorInterface $config,
        private DirectoriesInterface $dirs
    ) {
    }

    public function boot(ConsoleBootloader $console): void
    {
        $this->initConfig();

        $console->addCommand(SeedCommand::class);
    }

    private function initConfig(): void
    {
        $defaultDirectory = $this->dirs->get('app') . DatabaseSeederConfig::DEFAULT_DIRECTORY;

        $this->config->setDefaults(
            DatabaseSeederConfig::CONFIG,
            [
                'directory' => env(DatabaseSeederConfig::DIRECTORY_ENV_KEY, $defaultDirectory)
            ]
        );
    }
}
