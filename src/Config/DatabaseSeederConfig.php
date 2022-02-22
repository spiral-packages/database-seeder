<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Config;

use Spiral\Core\InjectableConfig;
use Spiral\Files\FilesInterface;

final class DatabaseSeederConfig extends InjectableConfig
{
    public const CONFIG = 'seeder';

    public const DEFAULT_SEEDERS_DIR = 'database' . FilesInterface::SEPARATOR . 'Seeder';
    public const DEFAULT_SEEDERS_NAMESPACE = 'Database\\Seeder';
    public const DEFAULT_FACTORIES_DIR = 'database' . FilesInterface::SEPARATOR . 'Factory';
    public const DEFAULT_FACTORIES_NAMESPACE = 'Database\\Factory';

    public const SEEDERS_DIR_ENV_KEY = 'DATABASE_SEEDERS_DIRECTORY';
    public const SEEDERS_NAMESPACE_ENV_KEY = 'DATABASE_SEEDERS_NAMESPACE';
    public const FACTORIES_DIR_ENV_KEY = 'DATABASE_FACTORIES_DIRECTORY';
    public const FACTORIES_NAMESPACE_ENV_KEY = 'DATABASE_FACTORIES_NAMESPACE';

    public function getSeedersDirectory(): string
    {
        return $this->config['seeders']['directory'];
    }

    public function getSeedersNamespace(): string
    {
        return $this->config['seeders']['namespace'];
    }

    public function getFactoriesDirectory(): string
    {
        return $this->config['factories']['directory'];
    }

    public function getFactoriesNamespace(): string
    {
        return $this->config['factories']['namespace'];
    }
}
