<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Config;

use Spiral\Core\InjectableConfig;

final class DatabaseSeederConfig extends InjectableConfig
{
    public const DEFAULT_DIRECTORY = 'seeders';
    public const CONFIG = 'seeder';
    public const DIRECTORY_ENV_KEY = 'DATABASE_SEEDERS_DIRECTORY';

    public function getDirectory(): string
    {
        return $this->config['directory'];
    }
}
