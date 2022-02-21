<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Tests\Fixture\Factory;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Spiral\DatabaseSeeder\Tests\Fixture\Entity\User;

class UserFactory extends AbstractFactory
{
    public function entity(): string
    {
        return User::class;
    }

    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'birthday' => \DateTimeImmutable::createFromMutable($this->faker->dateTime()),
        ];
    }
}
