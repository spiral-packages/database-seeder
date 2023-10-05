<?php

declare(strict_types=1);

namespace Tests\Unit\Config;

use PHPUnit\Framework\TestCase;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;

final class DatabaseSeederConfigTest extends TestCase
{
    public function testGetSeedersDirectory(): void
    {
        $config = new DatabaseSeederConfig();

        $this->assertSame(
            DatabaseSeederConfig::DEFAULT_SEEDERS_DIR,
            $config->getSeedersDirectory()
        );

        $config = new DatabaseSeederConfig(['seeders' => ['directory' => 'custom']]);

        $this->assertSame('custom', $config->getSeedersDirectory());
    }

    public function testGetSeedersNamespace(): void
    {
        $config = new DatabaseSeederConfig();

        $this->assertSame(
            DatabaseSeederConfig::DEFAULT_SEEDERS_NAMESPACE,
            $config->getSeedersNamespace()
        );

        $config = new DatabaseSeederConfig(['seeders' => ['namespace' => 'Custom']]);

        $this->assertSame('Custom', $config->getSeedersNamespace());
    }

    public function testGetSeedersBaseNamespace(): void
    {
        $config = new DatabaseSeederConfig();

        $this->assertSame(
            DatabaseSeederConfig::DEFAULT_SEEDERS_BASE_NAMESPACE,
            $config->getSeedersBaseNamespace()
        );

        $config = new DatabaseSeederConfig(['seeders' => ['baseNamespace' => 'BaseCustom']]);

        $this->assertSame('BaseCustom', $config->getSeedersBaseNamespace());
    }

    public function testGetFactoriesDirectory(): void
    {
        $config = new DatabaseSeederConfig();

        $this->assertSame(
            DatabaseSeederConfig::DEFAULT_FACTORIES_DIR,
            $config->getFactoriesDirectory()
        );

        $config = new DatabaseSeederConfig(['factories' => ['directory' => 'custom']]);

        $this->assertSame('custom', $config->getFactoriesDirectory());
    }

    public function testGetFactoriesNamespace(): void
    {
        $config = new DatabaseSeederConfig();

        $this->assertSame(
            DatabaseSeederConfig::DEFAULT_FACTORIES_NAMESPACE,
            $config->getFactoriesNamespace()
        );

        $config = new DatabaseSeederConfig(['factories' => ['namespace' => 'Custom']]);

        $this->assertSame('Custom', $config->getFactoriesNamespace());
    }

    public function testGetFactoriesBaseNamespace(): void
    {
        $config = new DatabaseSeederConfig();

        $this->assertSame(
            DatabaseSeederConfig::DEFAULT_FACTORIES_BASE_NAMESPACE,
            $config->getFactoriesBaseNamespace()
        );

        $config = new DatabaseSeederConfig(['factories' => ['baseNamespace' => 'BaseCustom']]);

        $this->assertSame('BaseCustom', $config->getFactoriesBaseNamespace());
    }
}
