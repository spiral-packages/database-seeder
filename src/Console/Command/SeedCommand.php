<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Console\Command;

use Spiral\Console\Command;
use Spiral\Console\Confirmation\ApplicationInProduction;
use Spiral\DatabaseSeeder\Seeder\Executor;
use Spiral\DatabaseSeeder\Seeder\Locator;
use Spiral\DatabaseSeeder\Seeder\SeederInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @property SymfonyStyle $output
 */
final class SeedCommand extends Command
{
    protected const NAME = 'db:seed';
    protected const DESCRIPTION = 'Seed the database with records';

    protected const ARGUMENTS = [
        ['class', InputArgument::OPTIONAL, 'The class name of the seeder', null],
        ['force', InputArgument::OPTIONAL, 'Force the operation to run when in production', null],
    ];

    public function perform(
        ApplicationInProduction $confirmation,
        Locator $locator,
        Executor $executor
    ): int {
        if (! $confirmation->confirmToProceed()) {
            return self::FAILURE;
        }

        $seeders = $locator->findSeeders($this->argument('class'));

        $executor->afterSeed(
            fn(SeederInterface $seeder) => $this->output->info(
                \sprintf('Seeding [%s] completed successfully.', $seeder::class)
            )
        );

        $executor->execute($this->output->progressIterate($seeders));

        $this->output->success('Database seeding completed successfully.');

        return self::SUCCESS;
    }
}
