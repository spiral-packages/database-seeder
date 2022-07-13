<?php

declare(strict_types=1);

namespace Tests\Fixture\Entity;

class User
{
    public int $id;
    public ?\DateTimeImmutable $birthday = null;
    public ?string $city = null;
    public bool $admin = false;

    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {
    }
}
