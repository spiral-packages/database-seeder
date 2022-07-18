<?php

declare(strict_types=1);

namespace Tests\App\Database;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(table: 'users')]
class User
{
    #[Column(type: 'primary')]
    public int $id;

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

    #[Column(type: 'boolean', typecast: 'bool')]
    public bool $admin = false;

    public function __construct(
        #[Column(type: 'string')]
        public string $firstName,
        #[Column(type: 'string')]
        public string $lastName
    ) {
    }
}
