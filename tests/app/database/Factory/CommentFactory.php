<?php

declare(strict_types=1);

namespace Tests\Database\Factory;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Tests\App\Database\Comment;

class CommentFactory extends AbstractFactory
{
    public function entity(): string
    {
        return Comment::class;
    }

    public function makeEntity(array $definition): object
    {
        return new Comment(
            $definition['text']
        );
    }

    public function definition(): array
    {
        return [
            'text' => $this->faker->randomHtml(),
            'author' => UserFactory::new()->makeOne(),
            'postedAt' => \DateTimeImmutable::createFromMutable($this->faker->dateTime()),
        ];
    }
}
