<?php

declare(strict_types=1);

namespace Tests\Functional\Bootloader;

use Spiral\Boot\DirectoriesInterface;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Tests\Functional\TestCase;

final class DefaultConfigurationBootloaderTest extends TestCase
{
    public function testDefaultConfiguration(): void
    {
        $dirs = $this->getContainer()->get(DirectoriesInterface::class);

        $target = [
            'seeders' => [
                'directory' => $dirs->get('app') . DatabaseSeederConfig::DEFAULT_SEEDERS_DIR,
                'namespace' => DatabaseSeederConfig::DEFAULT_SEEDERS_NAMESPACE,
            ],
            'factories' => [
                'directory' => $dirs->get('app') . DatabaseSeederConfig::DEFAULT_FACTORIES_DIR,
                'namespace' => DatabaseSeederConfig::DEFAULT_FACTORIES_NAMESPACE,
            ],
        ];

        $this->assertSame($target, $this->getConfig(DatabaseSeederConfig::CONFIG));
    }
}
