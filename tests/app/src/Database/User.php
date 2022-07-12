<?php

declare(strict_types=1);

namespace Tests\App\Database;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity(table: 'users')]
class User
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'string')]
    public string $firstName;

    #[Column(type: 'string')]
    public string $lastName;

    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTimeImmutable $birthday = null;

    #[Column(type: 'string', nullable: true)]
    public ?string $city = null;

    #[Column(type: 'int')]
    public int $age;

    #[Column(type: 'boolean', typecast: 'bool')]
    public bool $active;

    #[Column(type: 'float')]
    public float $someFloatVal;

    /** @var ArrayCollection<array-key, Post> */
    #[HasMany(target: Post::class, innerKey: 'id', outerKey: 'author_id', fkCreate: false, collection: 'doctrine')]
    public ArrayCollection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }
}
