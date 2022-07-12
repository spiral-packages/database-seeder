<?php

declare(strict_types=1);

namespace Tests\App\Database;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity(table: 'posts')]
class Post
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'text')]
    public string $content;

    #[BelongsTo(target: User::class, fkCreate: false)]
    public User $author;

    #[Column(type: 'datetime')]
    public \DateTimeImmutable $publishedAt;

    /** @var Comment[] */
    #[HasMany(target: Comment::class, innerKey: 'id', outerKey: 'post_id', fkCreate: false)]
    public array $comments = [];
}
