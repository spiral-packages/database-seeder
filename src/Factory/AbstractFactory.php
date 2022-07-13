<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Factory;

use Butschster\EntityFaker\EntityFactory\ClosureStrategy;
use Butschster\EntityFaker\EntityFactory\InstanceWithoutConstructorStrategy;
use Closure;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Laminas\Hydrator\ReflectionHydrator;
use Butschster\EntityFaker\Factory;
use Butschster\EntityFaker\LaminasEntityFactory;

abstract class AbstractFactory implements FactoryInterface
{
    /** @internal */
    private Factory $entityFactory;
    /** @var positive-int */
    private int $amount = 1;
    /** @var array<Closure> */
    private array $afterCreate = [];
    protected Generator $faker;
    /** @var array<Closure> */
    private array $states = [];
    /** @var array<Closure> */
    private array $entityStates = [];

    private function __construct(
        private readonly array $replaces = []
    ) {
        $this->faker = FakerFactory::create();

        $this->entityFactory = new Factory(
            new LaminasEntityFactory(
                new ReflectionHydrator(),
                new InstanceWithoutConstructorStrategy()
            ),
            $this->faker
        );
    }

    abstract public function makeEntity(array $definition): object;

    /**
     * @return class-string<T>
     */
    abstract public function entity(): string;

    abstract public function definition(): array;

    public static function new(array $replace = []): static
    {
        return new static($replace);
    }

    public function state(\Closure $state): self
    {
        $this->states[] = $state;

        return $this;
    }

    public function entityState(\Closure $state): self
    {
        $this->entityStates[] = $state;

        return $this;
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
        $entities = $this->make(fn() => $this->definition());
        if (! \is_array($entities)) {
            $entities = [$entities];
        }

        $this->callAfterCreating($entities);

        return $entities;
    }

    public function createOne(): object
    {
        $entity = $this->make(fn() => $this->definition());
        if (\is_array($entity)) {
            $entity = array_shift($entity);
        }

        $this->callAfterCreating([$entity]);

        return $entity;
    }

    /** @internal */
    private function make(callable $definition): object|array
    {
        $result = $this->entityFactory
            ->creationStrategy(
                $this->entity(),
                new ClosureStrategy(fn(string $class, array $data) => $this->makeEntity($data))
            )
            ->define($this->entity(), $definition)
            ->states($this->entity(), $this->states)
            ->of($this->entity())
            ->times($this->amount)
            ->make($this->replaces);

        if (\is_array($result)) {
            return \array_map(function (object $entity) {
                return $this->applyEntityState($entity);
            }, $result);
        }

        return $this->applyEntityState($result);
    }

    /** @internal */
    private function callAfterCreating(array $entities)
    {
        foreach ($entities as $entity) {
            \array_map(static fn(callable $callable) => $callable($entity), $this->afterCreate);
        }
    }

    /** @internal */
    private function applyEntityState(object $entity)
    {
        foreach ($this->entityStates as $state) {
            $entity = $state($entity);
        }

        return $entity;
    }
}
