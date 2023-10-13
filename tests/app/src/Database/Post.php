<?php

declare(strict_types=1);

namespace Tests\App\Database;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

#[Entity(table: 'posts')]
class Post
{
    #[Column(type: 'primary')]
    public int $id;

    #[BelongsTo(target: User::class, fkCreate: false)]
    public User $author;

    #[Column(type: 'datetime', name: 'published_at')]
    public \DateTimeImmutable $publishedAt;

    /** @var Comment[] */
    #[HasMany(target: Comment::class, innerKey: 'id', outerKey: 'post_id', fkCreate: false)]
    public array $comments = [];

    public function __construct(
        #[Column(type: 'text')]
        public string $content
    ) {
    }
}
