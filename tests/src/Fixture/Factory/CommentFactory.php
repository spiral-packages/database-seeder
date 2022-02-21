<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Tests\Fixture\Factory;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Spiral\DatabaseSeeder\Tests\Fixture\Entity\Comment;

class CommentFactory extends AbstractFactory
{
    public function entity(): string
    {
        return Comment::class;
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
