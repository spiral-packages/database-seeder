<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Console\Command;

use Spiral\DatabaseSeeder\Scaffolder\Declaration\FactoryDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FactoryCommand extends AbstractScaffolderCommand
{
    public const ELEMENT = 'factory';

    protected const NAME        = 'create:factory';
    protected const DESCRIPTION = 'Create factory for database seeding';
    protected const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Factory name'],
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
        /** @var FactoryDeclaration $declaration */
        $declaration = $this->createDeclaration();
        $declaration->declare();

        $this->writeDeclaration($declaration);

        return self::SUCCESS;
    }
}
