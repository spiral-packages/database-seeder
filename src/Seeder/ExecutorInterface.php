<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

interface ExecutorInterface
{
    /**
     * Execute all seeders classes and seed database.
     *
     * @psalm-param iterable<SeederInterface> $seeders
     */
    public function execute(iterable $seeders): void;

    /**
     * The event, calling after seeding each seeder class
     */
    public function afterSeed(callable $callback): self;
}
