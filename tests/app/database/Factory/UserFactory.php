<?php

declare(strict_types=1);

namespace Tests\Database\Factory;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Tests\App\Database\User;

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
            'age' => $this->faker->numberBetween(1, 90),
            'active' => $this->faker->boolean,
            'someFloatVal' => $this->faker->randomFloat()
        ];
    }
}
