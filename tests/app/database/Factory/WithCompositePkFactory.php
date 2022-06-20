<?php

declare(strict_types=1);

namespace Tests\Database\Factory;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Tests\App\Database\WithCompositePk;

class WithCompositePkFactory extends AbstractFactory
{
    public function entity(): string
    {
        return WithCompositePk::class;
    }

    public function definition(): array
    {
        return [
            'id' => $this->faker->randomDigit(),
            'otherId' => $this->faker->randomDigit(),
            'content' => $this->faker->sentence,
        ];
    }
}
