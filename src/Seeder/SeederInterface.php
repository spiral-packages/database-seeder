<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

interface SeederInterface
{
    /**
     * Seed the application's database.
     */
    public function __invoke(): void;

    /**
     * @psalm-return positive-int
     */
    public function getPriority(): int;
}
