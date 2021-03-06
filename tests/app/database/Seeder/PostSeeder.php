<?php

declare(strict_types=1);

namespace Tests\Database\Seeder;

use Spiral\DatabaseSeeder\Attribute\Seeder;
use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;
use Tests\Database\Factory\PostFactory;

#[Seeder(priority: 3)]
class PostSeeder extends AbstractSeeder
{
    public function run(): \Generator
    {
        yield from PostFactory::new()->times(10)->make();
    }
}
