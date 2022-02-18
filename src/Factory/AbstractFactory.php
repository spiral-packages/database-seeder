<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Factory;

use Faker\Factory as FakerFactory;
use Faker\Generator;
use Laminas\Hydrator\ReflectionHydrator;
use Butschster\EntityFaker\Factory;
use Butschster\EntityFaker\LaminasEntityFactory;

abstract class AbstractFactory implements FactoryInterface
{
    /** @internal */
    private Factory $entityFactory;
    private int $amount = 1;
    private array $afterCreate = [];
    protected Generator $faker;

    private function __construct(
        private array $replaces = []
    ) {
        $this->faker = FakerFactory::create();

        $this->entityFactory = new Factory(
            new LaminasEntityFactory(
                new ReflectionHydrator()
            ),
            $this->faker
        );
    }

    /** @psalm-return class-string */
    abstract public function entity(): string;

    abstract public function definition(): array;

    public static function new(array $replace = []): static
    {
        return new static($replace);
    }

    public function times(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function afterCreate(callable $afterCreate): self
    {
        $this->afterCreate[] = $afterCreate;

        return $this;
    }

    public function create(): array
    {
        $entities = $this->make([$this, 'definition']);
        if (!\is_array($entities)) {
            $entities = [$entities];
        }

        $this->callAfterCreating($entities);

        return $entities;
    }

    public function createOne(): object
    {
        $entity = $this->make([$this, 'definition']);
        if (\is_array($entity)) {
            $entity = array_shift($entity);
        }

        $this->callAfterCreating([$entity]);

        return $entity;
    }

    /** @internal */
    private function make(callable $definition): object|array
    {
        $this->entityFactory->define($this->entity(), $definition);

        return $this->entityFactory->of($this->entity())->times($this->amount)->make($this->replaces);
    }

    /** @internal */
    private function callAfterCreating(array $entities)
    {
        foreach ($entities as $entity) {
            \array_map(static fn(callable $callable) => $callable($entity), $this->afterCreate);
        }
    }
}
