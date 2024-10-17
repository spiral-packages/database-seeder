<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Factory;

use Butschster\EntityFaker\EntityFactory\ClosureStrategy;
use Butschster\EntityFaker\EntityFactory\InstanceWithoutConstructorStrategy;
use Closure;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\ORMInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Faker\Generator as Faker;
use Laminas\Hydrator\ReflectionHydrator;
use Butschster\EntityFaker\Factory;
use Butschster\EntityFaker\LaminasEntityFactory;
use Spiral\Core\ContainerScope;
use Spiral\DatabaseSeeder\Factory\Exception\FactoryException;
use Spiral\DatabaseSeeder\Factory\Exception\OutsideScopeException;

/**
 * @template TEntity of object
 * @implements FactoryInterface<TEntity>
 *
 * @psalm-import-type TDefinition from FactoryInterface
 * @psalm-type TState = Closure(Faker, TDefinition):TDefinition
 * @psalm-type TEntityState = Closure(TEntity):TEntity
 * @psalm-type TCallback = Closure(TEntity):void
 *
 * @property-read $data TDefinition|TDefinition[] Will return an array if {@see static::$amount} is greater than 1 otherwise will return a single entity.
 * @property-read $entity TEntity
 * @property-read $entities TEntity[]
 */
abstract class AbstractFactory implements FactoryInterface
{
    /** @internal */
    private Factory $entityFactory;
    /** @psalm-var positive-int */
    private int $amount = 1;
    /** @var TCallback[] */
    private array $afterCreate = [];
    /** @var TCallback[] */
    private array $afterMake = [];
    /** @var TState[] */
    private array $states = [];
    /** @var TEntityState[] */
    private array $entityStates = [];

    protected Generator $faker;

    public static bool $cleanHeap = false;

    /**
     * @param TDefinition $replaces
     */
    private function __construct(
        private readonly array $replaces = [],
    ) {
        $this->faker = FakerFactory::create();

        $this->entityFactory = new Factory(
            new LaminasEntityFactory(
                new ReflectionHydrator(),
                new InstanceWithoutConstructorStrategy(),
            ),
            $this->faker,
        );
    }

    /**
     * In this method you should describe how to create an entity with the given definition.
     *
     * @return TEntity
     */
    abstract public function makeEntity(array $definition): object;

    public static function new(array $replace = []): static
    {
        return new static($replace);
    }

    public function times(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Define a state for the entity using a closure with the given definition.
     *
     * Example:
     * <code>
     * $factory->state(fn(\Faker\Generator $faker, array $definition) => [
     *      'admin' => $faker->boolean(),
     * ])->times(10)->create();
     * </code>
     *
     * Example usage in factory:
     * <code>
     * public function admin(): self
     * {
     *      return $this->state(fn(\Faker\Generator $faker, array $definition) => [
     *          'admin' => true,
     *      ]);
     * }
     * </code>
     *
     * @param TState $state
     *
     */
    public function state(Closure $state): self
    {
        $this->states[] = $state;

        return $this;
    }

    /**
     * Define a state for the entity using a closure with the given entity.
     *
     * Example:
     * <code>
     * $factory->entityState(static function(User $user) {
     *      return $user->markAsDeleted();
     * })->times(10)->create();
     * </code>
     *
     * Example usage in factory:
     * <code>
     * public function withBirthday(\DateTimeImmutable $date): self
     * {
     *      return $this->entityState(static function (User $user) use ($date) {
     *          $user->birthday = $date;
     *          return $user;
     *      });
     * }
     * </code>
     *
     * @param TEntityState $state
     */
    public function entityState(Closure $state): self
    {
        $this->entityStates[] = $state;

        return $this;
    }

    /**
     * Define a callback to run after creating a model.
     *
     * Example:
     *
     * <code>
     * $factory->afterCreate(static function(User $user): void {
     *       $user->markAsDeleted();
     * })->create();
     * </code>
     *
     * @param TCallback $afterCreate
     */
    public function afterCreate(callable $afterCreate): self
    {
        $this->afterCreate[] = $afterCreate;

        return $this;
    }

    /**
     * Define a callback to run after making a model.
     *
     * Example:
     *
     * <code>
     * $factory->afterMake(static function(User $user): void {
     *      $user->verify();
     * })->create();
     * </code>
     *
     * @param TCallback $afterMake
     */
    public function afterMake(callable $afterMake): self
    {
        $this->afterMake[] = $afterMake;

        return $this;
    }

    /**
     * Create many entities with persisting them to the database.
     *
     * @param bool|null $cleanHeap Clean the heap after creating entities.
     *
     * @note To change the default value use {@see static::$cleanHeap} property.
     *
     * @return TEntity[]
     */
    public function create(?bool $cleanHeap = null): array
    {
        $entities = $this->object(fn() => $this->definition());
        if (!\is_array($entities)) {
            $entities = [$entities];
        }

        $this->storeEntities($entities, $cleanHeap);

        $this->callAfterCreating($entities);

        return $entities;
    }

    /**
     * Create an entity with persisting it to the database.
     *
     * @param bool|null $cleanHeap Clean the heap after creating entity. Default value is false.
     *
     * @note To change the default value use {@see static::$cleanHeap} property.
     *
     * @return TEntity
     */
    public function createOne(?bool $cleanHeap = null): object
    {
        $entity = $this->object(fn() => $this->definition());
        if (\is_array($entity)) {
            $entity = \array_shift($entity);
        }

        $this->storeEntities([$entity], $cleanHeap);

        $this->callAfterCreating([$entity]);

        return $entity;
    }

    /**
     * @return TEntity[]
     */
    public function make(): array
    {
        $entities = $this->object(fn() => $this->definition());
        if (!\is_array($entities)) {
            $entities = [$entities];
        }

        return $entities;
    }

    /**
     * @return TEntity
     */
    public function makeOne(): object
    {
        $entity = $this->object(fn() => $this->definition());
        if (\is_array($entity)) {
            $entity = \array_shift($entity);
        }

        return $entity;
    }

    /**
     * Get the raw array data. This data will be used to make an entity or entities.
     *
     * @param TState $definition
     * @return TDefinition|TDefinition[] Will return an array if {@see static::$amount} is greater than 1 otherwise will return a single entity.
     */
    public function raw(Closure $definition): array
    {
        $this->entityFactory->define($this->entity(), $definition);

        $data = $this->entityFactory->of($this->entity())->times($this->amount)->raw($this->replaces);

        return \array_is_list($data) ? $data[0] : $data;
    }

    public function __get(string $name): mixed
    {
        return match ($name) {
            'data' => $this->raw(fn() => $this->definition()),
            'entity' => $this->createOne(),
            'entities' => $this->create(),
            default => throw new FactoryException('Undefined magic property.')
        };
    }

    private function storeEntities(array $entities, ?bool $cleanHeap = null): void
    {
        $container = ContainerScope::getContainer();
        if ($container === null) {
            throw new OutsideScopeException(
                \sprintf(
                    'The container is not available. Make sure [%s] method is running in the ContainerScope.',
                    __METHOD__,
                ),
            );
        }

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        foreach ($entities as $entity) {
            $em->persist($entity);
        }
        $em->run();

        if ($cleanHeap ?? self::$cleanHeap) {
            $container->get(ORMInterface::class)->getHeap()->clean();
        }
    }

    /** @internal */
    private function object(Closure $definition): object|array
    {
        $entityClass = $this->entity();

        $this->entityFactory
            ->creationStrategy(
                $entityClass,
                new ClosureStrategy(
                    fn(string $class, array $data): object => $this->makeEntity($data),
                ),
            )
            ->define($entityClass, $definition)
            ->states($entityClass, $this->states);

        foreach ($this->afterMake as $afterMakeCallable) {
            $this->entityFactory->afterMaking($entityClass, $afterMakeCallable);
        }

        /** @var TEntity|TEntity[] $result */
        $result = $this->entityFactory->of($entityClass)->times($this->amount)->make($this->replaces);

        if (\is_array($result)) {
            return \array_map(
                fn(object $entity): object => $this->applyEntityState($entity),
                $result,
            );
        }

        return $this->applyEntityState($result);
    }

    /** @internal */
    private function applyEntityState(object $entity): object
    {
        foreach ($this->entityStates as $state) {
            $entity = $state($entity);
        }

        return $entity;
    }

    /** @internal */
    private function callAfterCreating(array $entities): void
    {
        foreach ($entities as $entity) {
            \array_map(static fn(callable $callable) => $callable($entity), $this->afterCreate);
        }
    }
}
