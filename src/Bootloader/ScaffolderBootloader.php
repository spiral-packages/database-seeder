<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Bootloader;

use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\DatabaseSeeder\Scaffolder\Declaration;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader as BaseScaffolderBootloader;

class ScaffolderBootloader extends Bootloader
{
    public const DEPENDENCIES = [
        ConfigurationBootloader::class,
        BaseScaffolderBootloader::class,
    ];

    public function boot(BaseScaffolderBootloader $scaffolder, DatabaseSeederConfig $config): void
    {
        $scaffolder->addDeclaration(Declaration\FactoryDeclaration::TYPE, [
            'namespace' => $config->getFactoriesNamespace(),
            'postfix' => 'Factory',
            'class' => Declaration\FactoryDeclaration::class,
            'baseNamespace' => $config->getFactoriesBaseNamespace(),
            'directory' => $config->getFactoriesDirectory()
        ]);

        $scaffolder->addDeclaration(Declaration\SeederDeclaration::TYPE, [
            'namespace' => $config->getSeedersNamespace(),
            'postfix' => 'Seeder',
            'class' => Declaration\SeederDeclaration::class,
            'baseNamespace' => $config->getSeedersBaseNamespace(),
            'directory' => $config->getSeedersDirectory()
        ]);
    }
}
