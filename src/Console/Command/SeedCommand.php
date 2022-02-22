<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Console\Command;

use Spiral\Console\Command;
use Spiral\DatabaseSeeder\Seeder\Executor;
use Spiral\DatabaseSeeder\Seeder\Locator;
use Spiral\DatabaseSeeder\Seeder\SeederInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class SeedCommand extends Command
{
    protected const NAME = 'db:seed';
    protected const DESCRIPTION = 'Seed the database with records';

    protected const ARGUMENTS = [
        ['class', InputArgument::OPTIONAL, 'The class name of the seeder', null],
        ['force', InputArgument::OPTIONAL, 'Force the operation to run when in production', null],
    ];

    public function perform(Locator $locator, Executor $executor): int
    {
        /** @psalm-suppress PossiblyNullArgument */
        $io = new SymfonyStyle($this->input, $this->output);

        $seeders = $locator->findSeeders($this->argument('class'));

        $executor->afterSeed(
            static fn(SeederInterface $seeder) => $io->info(
                \sprintf('Seeding [%s] completed successfully.', $seeder::class)
            )
        );

        $executor->execute($io->progressIterate($seeders));

        $io->success('Database seeding completed successfully.');

        return self::SUCCESS;
    }
}
