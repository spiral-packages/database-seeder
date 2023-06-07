<?php

declare(strict_types=1);

namespace Tests\Functional\Bootloader;

use Spiral\Boot\DirectoriesInterface;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\DatabaseSeeder\Scaffolder\Declaration;
use Spiral\Scaffolder\Config\ScaffolderConfig;
use Tests\Functional\TestCase;

final class ScaffolderBootloaderTest extends TestCase
{
    public function testScaffolderConfigsShouldByAdded(): void
    {
        $dirs = $this->getContainer()->get(DirectoriesInterface::class);
        $config = $this->getContainer()->get(DatabaseSeederConfig::class);
        $declarations = $this->getConfig(ScaffolderConfig::CONFIG)['defaults']['declarations'];

        $this->assertSame(
            [
                'namespace' => $config->getFactoriesNamespace(),
                'postfix' => 'Factory',
                'class' => Declaration\FactoryDeclaration::class,
                'baseNamespace' => DatabaseSeederConfig::DEFAULT_FACTORIES_BASE_NAMESPACE,
                'directory' => $dirs->get('app') . DatabaseSeederConfig::DEFAULT_FACTORIES_DIR
            ],
            $declarations['factory']
        );

        $this->assertSame(
            [
                'namespace' => $config->getSeedersNamespace(),
                'postfix'   => 'Seeder',
                'class'     => Declaration\SeederDeclaration::class,
                'baseNamespace' => DatabaseSeederConfig::DEFAULT_SEEDERS_BASE_NAMESPACE,
                'directory' => $dirs->get('app') . DatabaseSeederConfig::DEFAULT_SEEDERS_DIR
            ],
            $declarations['seeder']
        );
    }
}
