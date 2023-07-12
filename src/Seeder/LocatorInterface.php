<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

interface LocatorInterface
{
    /**
     * @template T of SeederInterface
     * @return array<class-string<T>, T>
     */
    public function find(): array;
}
