<?php

declare(strict_types=1);

namespace Tests\Functional\Bootloader;

use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\DatabaseSeeder\Scaffolder\Declaration;
use Spiral\Scaffolder\Config\ScaffolderConfig;
use Tests\Functional\TestCase;

final class ScaffolderBootloaderTest extends TestCase
{
    public function testScaffolderConfigsShouldByAdded(): void
    {
        $config = $this->getContainer()->get(DatabaseSeederConfig::class);
        $declarations = $this->getConfig(ScaffolderConfig::CONFIG)['declarations'];

        $this->assertSame(
            [
                'namespace' => $config->getFactoriesNamespace(),
                'postfix'   => 'Factory',
                'class'     => Declaration\FactoryDeclaration::class,
                'baseNamespace' => ''
            ],
            $declarations['factory']
        );

        $this->assertSame(
            [
                'namespace' => $config->getSeedersNamespace(),
                'postfix'   => 'Seeder',
                'class'     => Declaration\SeederDeclaration::class,
                'baseNamespace' => ''
            ],
            $declarations['seeder']
        );
    }
}
