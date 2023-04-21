<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Console\Command;

use Spiral\DatabaseSeeder\Scaffolder\Declaration\FactoryDeclaration;
use Spiral\Scaffolder\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @property SymfonyStyle $output
 */
final class FactoryCommand extends AbstractCommand
{
    protected const NAME = 'create:factory';
    protected const DESCRIPTION = 'Create factory for database seeding';
    protected const ARGUMENTS = [
        ['name', InputArgument::REQUIRED, 'Factory name'],
    ];
    protected const OPTIONS = [
        [
            'comment',
            'c',
            InputOption::VALUE_OPTIONAL,
            'Optional comment to add as class header',
        ],
    ];

    /**
     * Create repository declaration.
     */
    public function perform(): int
    {
        /** @var FactoryDeclaration $declaration */
        $declaration = $this->createDeclaration(FactoryDeclaration::class);

        $this->writeDeclaration($declaration);

        $this->output->success("Factory {$declaration->getClass()->getName()} has been successfully created.");

        return self::SUCCESS;
    }
}
