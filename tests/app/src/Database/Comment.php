<?php

declare(strict_types=1);

namespace Tests\App\Database;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasOne;

#[Entity(table: 'comments')]
class Comment
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'text')]
    public string $text;

    #[HasOne(target: User::class, fkCreate: false)]
    public User $author;

    #[HasOne(target: Post::class, fkCreate: false)]
    public Post $post;

    #[Column(type: 'datetime')]
    public \DateTimeImmutable $postedAt;
}
