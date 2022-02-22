<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

interface SeederInterface
{
    /**
     * Returns iterable database entities.
     */
    public function run(): \Generator;

    /**
     * @psalm-return positive-int
     */
    public function getPriority(): int;
}
