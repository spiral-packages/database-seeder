<?php

declare(strict_types=1);

namespace Tests\Fixture\Entity;

class Comment
{
    public int $id;
    public string $text;
    public User $author;
    public Post $post;
    public \DateTimeImmutable $postedAt;
}
