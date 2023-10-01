<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Factory;

/**
 * @template TEntity of object
 */
interface FactoryInterface
{
    /**
     * Get the entity class name.
     *
     * @psalm-return class-string<TEntity>
     */
    public function entity(): string;

    public static function new(): static;

    /**
     * Entity data definition. This data will be used to create an entity.
     */
    public function definition(): array;

    /**
     * How many entities should be created.
     *
     * @psalm-param positive-int $amount
     */
    public function times(int $amount): self;

    /**
     * Create many entities with persisting them to the database.
     *
     * @return TEntity[]
     */
    public function create(): array;

    /**
     * Create an entity with persisting it to the database.
     *
     * @return TEntity
     */
    public function createOne(): object;

    /**
     * Make many entities without persisting them to the database.
     *
     * @return TEntity[]
     */
    public function make(): array;

    /**
     * Make an entity without persisting it to the database.
     * @return TEntity
     */
    public function makeOne(): object;
}
