<?php

declare(strict_types=1);

namespace Tests\Functional\Bootloader;

use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Tests\TestCase;

final class ConfigurationBootloaderTest extends TestCase
{
    public const ENV = [
        DatabaseSeederConfig::SEEDERS_DIR_ENV_KEY => 'seeders-dir',
        DatabaseSeederConfig::SEEDERS_NAMESPACE_ENV_KEY => 'seeders-namespace',
        DatabaseSeederConfig::FACTORIES_DIR_ENV_KEY => 'factories-dir',
        DatabaseSeederConfig::FACTORIES_NAMESPACE_ENV_KEY => 'factories-namespace',
    ];

    public function testChangedFromEnv(): void
    {
        $target = [
            'seeders' => [
                'directory' => 'seeders-dir',
                'namespace' => 'seeders-namespace',
            ],
            'factories' => [
                'directory' => 'factories-dir',
                'namespace' => 'factories-namespace',
            ],
        ];

        $this->assertSame(
            $target,
            $this->getConfig(DatabaseSeederConfig::CONFIG)
        );
    }
}
