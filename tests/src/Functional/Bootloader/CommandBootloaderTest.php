<?php

declare(strict_types=1);

namespace Tests\Functional\Bootloader;

use Spiral\Console\Config\ConsoleConfig;
use Tests\Functional\TestCase;
use Spiral\DatabaseSeeder\Console\Command;

final class CommandBootloaderTest extends TestCase
{
    public function testCommandsShouldBeRegistered(): void
    {
        $commands = [
            Command\FactoryCommand::class,
            Command\SeedCommand::class,
            Command\SeederCommand::class,
        ];

        $registeredCommands = $this->getConfig(ConsoleConfig::CONFIG)['commands'] ?? [];

        foreach ($commands as $command) {
            $this->assertContains($command, $registeredCommands);
        }
    }
}
