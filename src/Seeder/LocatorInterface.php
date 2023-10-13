<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

interface LocatorInterface
{
    /**
     * @return array<class-string<SeederInterface>, SeederInterface>
     */
    public function find(): array;
}
