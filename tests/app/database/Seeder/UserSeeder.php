<?php

declare(strict_types=1);

namespace Tests\Database\Seeder;

use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;
use Tests\Database\Factory\UserFactory;

class UserSeeder extends AbstractSeeder
{
    protected int $priority = 5;

    public function run(): \Generator
    {
        yield UserFactory::new()->makeOne();
    }
}
