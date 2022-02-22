<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Console\Bootloader\ConsoleBootloader;
use Spiral\DatabaseSeeder\Console\Command;

class CommandBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ConsoleBootloader::class
    ];

    public function boot(ConsoleBootloader $console): void
    {
        $console->addCommand(Command\FactoryCommand::class);
        $console->addCommand(Command\SeedCommand::class);
        $console->addCommand(Command\SeederCommand::class);
    }
}
