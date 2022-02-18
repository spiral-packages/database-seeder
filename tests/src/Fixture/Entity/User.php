<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Tests\Fixture\Entity;

class User
{
    public int $id;
    public string $firstName;
    public string $lastName;
    public ?\DateTimeImmutable $birthday = null;
    public ?string $city = null;
}
