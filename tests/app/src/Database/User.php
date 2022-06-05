<?php

declare(strict_types=1);

namespace Tests\App\Database;

class User
{
    public int $id;
    public string $firstName;
    public string $lastName;
    public ?\DateTimeImmutable $birthday = null;
    public ?string $city = null;
    public int $age;
    public bool $active;
    public float $someFloatVal;
}
