<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Config\ConfiguratorInterface;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig as Config;

class ConfigurationBootloader extends Bootloader
{
    public function __construct(
        private ConfiguratorInterface $config,
        private DirectoriesInterface $dirs
    ) {
    }

    public function boot(): void
    {
        $this->initConfig();
    }

    private function initConfig(): void
    {
        $defaultSeedersDir = $this->dirs->get('app') . Config::DEFAULT_SEEDERS_DIR;
        $defaultFactoriesDir = $this->dirs->get('app') . Config::DEFAULT_FACTORIES_DIR;

        $this->config->setDefaults(
            Config::CONFIG,
            [
                'seeders' => [
                    'directory' => env(Config::SEEDERS_DIR_ENV_KEY, $defaultSeedersDir),
                    'namespace' => env(Config::SEEDERS_NAMESPACE_ENV_KEY, Config::DEFAULT_SEEDERS_NAMESPACE),
                ],
                'factories' => [
                    'directory' => env(Config::FACTORIES_DIR_ENV_KEY, $defaultFactoriesDir),
                    'namespace' => env(Config::FACTORIES_NAMESPACE_ENV_KEY, Config::DEFAULT_FACTORIES_NAMESPACE),
                ],
            ]
        );
    }
}
