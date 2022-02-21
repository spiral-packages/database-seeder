<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Tests\Fixture\Factory;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Spiral\DatabaseSeeder\Tests\Fixture\Entity\Post;

class PostFactory extends AbstractFactory
{
    public function entity(): string
    {
        return Post::class;
    }

    public function definition(): array
    {
        return [
            'content' => $this->faker->randomHtml(),
            'author' => UserFactory::new()->createOne(),
            'publishedAt' => \DateTimeImmutable::createFromMutable($this->faker->dateTime()),
            'comments' => CommentFactory::new()->times(3)->create(),
        ];
    }
}
