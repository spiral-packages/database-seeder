<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;

/**
 * Contains all package bootloaders
 */
class DatabaseSeederBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ConfigurationBootloader::class,
        CommandBootloader::class,
        // ScaffolderBootloader::class, // TODO waiting SF 2.10
    ];
}
