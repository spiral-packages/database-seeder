<?php

declare(strict_types=1);

namespace Tests\Fixture\Seeder;

use Spiral\DatabaseSeeder\Attribute\Seeder;
use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;
use Tests\Fixture\Factory\PostFactory;

#[Seeder(priority: 3)]
class PostSeeder extends AbstractSeeder
{
    public function run(): \Generator
    {
        yield from PostFactory::new()->times(10)->create();
    }
}
