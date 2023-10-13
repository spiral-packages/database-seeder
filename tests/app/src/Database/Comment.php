<?php

declare(strict_types=1);

namespace Tests\App\Database;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

#[Entity(table: 'comments')]
class Comment
{
    #[Column(type: 'primary')]
    public int $id;

    #[BelongsTo(target: User::class, fkCreate: false)]
    public User $author;

    #[BelongsTo(target: Post::class, fkCreate: false)]
    public Post $post;

    #[Column(type: 'datetime', name: 'posted_at')]
    public \DateTimeImmutable $postedAt;

    public function __construct(
        #[Column(type: 'text')]
        public string $text
    ) {
    }
}
