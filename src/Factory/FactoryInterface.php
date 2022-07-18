<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Factory;

interface FactoryInterface
{
    /** @psalm-return class-string */
    public function entity(): string;

    public function definition(): array;

    public static function new(): static;

    /** @psalm-param positive-int $amount */
    public function times(int $amount): self;

    public function create(): array;

    public function createOne(): object;

    public function make(): array;

    public function makeOne(): object;
}
