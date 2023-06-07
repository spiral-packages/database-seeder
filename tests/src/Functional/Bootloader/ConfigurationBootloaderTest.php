<?php

declare(strict_types=1);

namespace Tests\Functional\Bootloader;

use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Tests\Functional\TestCase;

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
                'baseNamespace' => DatabaseSeederConfig::DEFAULT_SEEDERS_BASE_NAMESPACE,
            ],
            'factories' => [
                'directory' => 'factories-dir',
                'namespace' => 'factories-namespace',
                'baseNamespace' => DatabaseSeederConfig::DEFAULT_FACTORIES_BASE_NAMESPACE,
            ],
        ];

        $this->assertSame(
            $target,
            $this->getConfig(DatabaseSeederConfig::CONFIG)
        );
    }
}
