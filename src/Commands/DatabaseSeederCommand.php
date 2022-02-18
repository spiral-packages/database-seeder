<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Commands;

use Spiral\Console\Command;

class DatabaseSeederCommand extends Command
{
    protected const NAME = 'database-seeder';
    protected const DESCRIPTION = 'My command';
    protected const ARGUMENTS = [];

    public function perform(): int
    {
        return self::SUCCESS;
    }
}
