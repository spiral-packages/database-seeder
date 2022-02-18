<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Config;

use Spiral\Core\InjectableConfig;

final class DatabaseSeederConfig extends InjectableConfig
{
    public const CONFIG = 'database-seeder';
    protected $config = [];
}
