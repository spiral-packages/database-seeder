<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Config\ConfiguratorInterface;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig as Config;

class ConfigurationBootloader extends Bootloader
{
    public function __construct(
        private ConfiguratorInterface $config,
        private DirectoriesInterface $dirs
    ) {
    }

    public function init(EnvironmentInterface $env): void
    {
        $this->initConfig($env);
    }

    private function initConfig(EnvironmentInterface $env): void
    {
        $defaultSeedersDir = $this->dirs->get('app') . Config::DEFAULT_SEEDERS_DIR;
        $defaultFactoriesDir = $this->dirs->get('app') . Config::DEFAULT_FACTORIES_DIR;

        $this->config->setDefaults(
            Config::CONFIG,
            [
                'seeders' => [
                    'directory' => $env->get(Config::SEEDERS_DIR_ENV_KEY, $defaultSeedersDir),
                    'namespace' => $env->get(Config::SEEDERS_NAMESPACE_ENV_KEY, Config::DEFAULT_SEEDERS_NAMESPACE),
                    'baseNamespace' => Config::DEFAULT_SEEDERS_BASE_NAMESPACE
                ],
                'factories' => [
                    'directory' => $env->get(Config::FACTORIES_DIR_ENV_KEY, $defaultFactoriesDir),
                    'namespace' => $env->get(Config::FACTORIES_NAMESPACE_ENV_KEY, Config::DEFAULT_FACTORIES_NAMESPACE),
                    'baseNamespace' => Config::DEFAULT_FACTORIES_BASE_NAMESPACE
                ],
            ]
        );
    }
}
