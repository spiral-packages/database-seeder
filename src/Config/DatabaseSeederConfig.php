<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Config;

use Spiral\Core\InjectableConfig;

final class DatabaseSeederConfig extends InjectableConfig
{
    public const CONFIG = 'seeder';

    public const DEFAULT_SEEDERS_DIR = 'database';
    public const DEFAULT_SEEDERS_NAMESPACE = 'Seeder';
    public const DEFAULT_SEEDERS_BASE_NAMESPACE = 'Database';
    public const DEFAULT_FACTORIES_DIR = 'database';
    public const DEFAULT_FACTORIES_NAMESPACE = 'Factory';
    public const DEFAULT_FACTORIES_BASE_NAMESPACE = 'Database';

    public const SEEDERS_DIR_ENV_KEY = 'DATABASE_SEEDERS_DIRECTORY';
    public const SEEDERS_NAMESPACE_ENV_KEY = 'DATABASE_SEEDERS_NAMESPACE';
    public const FACTORIES_DIR_ENV_KEY = 'DATABASE_FACTORIES_DIRECTORY';
    public const FACTORIES_NAMESPACE_ENV_KEY = 'DATABASE_FACTORIES_NAMESPACE';

    protected array $config = [
        'seeders' => [
            'directory' => self::DEFAULT_SEEDERS_DIR,
            'namespace' => self::DEFAULT_SEEDERS_NAMESPACE,
            'baseNamespace' => self::DEFAULT_SEEDERS_BASE_NAMESPACE,
        ],
        'factories' => [
            'directory' => self::DEFAULT_FACTORIES_DIR,
            'namespace' => self::DEFAULT_FACTORIES_NAMESPACE,
            'baseNamespace' => self::DEFAULT_FACTORIES_BASE_NAMESPACE,
        ],
    ];

    public function getSeedersDirectory(): string
    {
        return $this->config['seeders']['directory'];
    }

    public function getSeedersNamespace(): string
    {
        return $this->config['seeders']['namespace'];
    }

    public function getSeedersBaseNamespace(): string
    {
        return $this->config['seeders']['baseNamespace'];
    }


    public function getFactoriesDirectory(): string
    {
        return $this->config['factories']['directory'];
    }

    public function getFactoriesNamespace(): string
    {
        return $this->config['factories']['namespace'];
    }

    public function getFactoriesBaseNamespace(): string
    {
        return $this->config['factories']['baseNamespace'];
    }
}
