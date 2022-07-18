<?php

declare(strict_types=1);

namespace Tests\Database\Factory;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Tests\App\Database\Post;

class PostFactory extends AbstractFactory
{
    public function entity(): string
    {
        return Post::class;
    }

    public function makeEntity(array $definition): Post
    {
        return new Post(
            $definition['content']
        );
    }

    public function definition(): array
    {
        return [
            'content' => $this->faker->randomHtml(),
            'author' => UserFactory::new()->makeOne(),
            'publishedAt' => \DateTimeImmutable::createFromMutable($this->faker->dateTime()),
            'comments' => CommentFactory::new()->times(3)->make(),
        ];
    }
}
