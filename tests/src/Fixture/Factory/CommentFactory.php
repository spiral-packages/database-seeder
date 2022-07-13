<?php

declare(strict_types=1);

namespace Tests\Fixture\Factory;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Tests\Fixture\Entity\Comment;

class CommentFactory extends AbstractFactory
{
    public function entity(): string
    {
        return Comment::class;
    }

    public function makeEntity(array $definition): Comment
    {
        return new Comment(
            $definition['text']
        );
    }

    public function definition(): array
    {
        return [
            'text' => $this->faker->randomHtml(),
            'author' => UserFactory::new()->createOne(),
            'postedAt' => \DateTimeImmutable::createFromMutable($this->faker->dateTime()),
        ];
    }
}
