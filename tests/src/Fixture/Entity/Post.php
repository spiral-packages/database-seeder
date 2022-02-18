<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Tests\Fixture\Entity;

class Post
{
    public int $id;
    public string $content;
    public User $author;
    public \DateTimeImmutable $publishedAt;

    /** @var Comment[] */
    public array $comments = [];
}
