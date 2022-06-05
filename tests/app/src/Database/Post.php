<?php

declare(strict_types=1);

namespace Tests\App\Database;

class Post
{
    public int $id;
    public string $content;
    public User $author;
    public \DateTimeImmutable $publishedAt;

    /** @var Comment[] */
    public array $comments = [];
}
