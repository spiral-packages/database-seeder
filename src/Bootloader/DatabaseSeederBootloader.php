<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\DatabaseSeeder\Seeder\AttributeLocator;
use Spiral\DatabaseSeeder\Seeder\DirectoryLocator;
use Spiral\DatabaseSeeder\Seeder\Locator;

/**
 * Contains all package bootloaders
 */
class DatabaseSeederBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ConfigurationBootloader::class,
        CommandBootloader::class,
        ScaffolderBootloader::class,
    ];

    protected const SINGLETONS = [
        Locator::class => [self::class, 'initLocator'],
    ];

    private function initLocator(AttributeLocator $attributeLocator, DirectoryLocator $directoryLocator): Locator
    {
        return new Locator(
            $directoryLocator,
            $attributeLocator,
        );
    }
}
