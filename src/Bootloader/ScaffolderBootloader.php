<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Bootloader;

use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\DatabaseSeeder\Scaffolder\Declaration;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader as BaseScaffolderBootloader;

final class ScaffolderBootloader extends Bootloader
{
    public const DEPENDENCIES = [
        ConfigurationBootloader::class,
        BaseScaffolderBootloader::class,
    ];

    public function init(BaseScaffolderBootloader $scaffolder, DatabaseSeederConfig $config): void
    {
        $scaffolder->addDeclaration('factory', [
            'namespace' => $config->getFactoriesNamespace(),
            'postfix' => 'Factory',
            'class' => Declaration\FactoryDeclaration::class,
        ]);

        $scaffolder->addDeclaration('seeder', [
            'namespace' => '',
            'postfix' => 'Seeder',
            'class' => Declaration\SeederDeclaration::class,
        ]);
    }
}
