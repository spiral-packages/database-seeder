<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Console\Command;

use Spiral\DatabaseSeeder\Scaffolder\Declaration\SeederDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SeederCommand extends AbstractScaffolderCommand
{
    public const ELEMENT = 'seeder';

    protected const NAME        = 'create:seeder';
    protected const DESCRIPTION = 'Create seeder for database seeding';
    protected const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Seeder name'],
    ];
    protected const OPTIONS     = [
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
        /** @var SeederDeclaration $declaration */
        $declaration = $this->createDeclaration();
        $declaration->declare();

        $this->writeDeclaration($declaration);

        return self::SUCCESS;
    }
}
