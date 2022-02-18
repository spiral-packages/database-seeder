<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Factory;

interface FactoryInterface
{
    /** @psalm-return class-string */
    public function entity(): string;

    public function definition(): array;

    public static function new(): static;

    public function times(int $amount): self;

    public function create(): array;

    public function createOne(): object;
}
