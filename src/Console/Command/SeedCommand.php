<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Console\Command;

use Spiral\Console\Command;
use Spiral\DatabaseSeeder\Seeder\Locator;
use Symfony\Component\Console\Input\InputArgument;

class SeedCommand extends Command
{
    protected const NAME = 'db:seed';
    protected const DESCRIPTION = 'Seed the database with records';

    protected const ARGUMENTS = [
        ['class', InputArgument::OPTIONAL, 'The class name of the seeder', null],
        ['database', InputArgument::OPTIONAL, 'The database connection to seed', null],
        ['force', InputArgument::OPTIONAL, 'Force the operation to run when in production', null],
    ];

    public function perform(Locator $locator): int
    {
        $seeders = $locator->findSeeders($this->argument('class')); // TODO add seeding logic

        return self::SUCCESS;
    }
}
