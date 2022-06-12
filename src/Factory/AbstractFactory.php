<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Factory;

use Cycle\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Laminas\Hydrator\ReflectionHydrator;
use Butschster\EntityFaker\Factory;
use Butschster\EntityFaker\LaminasEntityFactory;
use Spiral\Core\ContainerScope;
use Spiral\DatabaseSeeder\Factory\Exception\FactoryException;
use Spiral\DatabaseSeeder\Factory\Exception\OutsideScopeException;

/**
 * @property-read $data
 */
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
        $entities = $this->object([$this, 'definition']);
        if (!\is_array($entities)) {
            $entities = [$entities];
        }

        $this->storeEntities($entities);

        $this->callAfterCreating($entities);

        return $entities;
    }

    public function createOne(): object
    {
        $entity = $this->object([$this, 'definition']);
        if (\is_array($entity)) {
            $entity = \array_shift($entity);
        }

        $this->storeEntities([$entity]);

        $this->callAfterCreating([$entity]);

        return $entity;
    }

    public function make(): array
    {
        $entities = $this->object([$this, 'definition']);
        if (!\is_array($entities)) {
            $entities = [$entities];
        }

        $this->callAfterCreating($entities);

        return $entities;
    }

    public function makeOne(): object
    {
        $entity = $this->object([$this, 'definition']);
        if (\is_array($entity)) {
            $entity = \array_shift($entity);
        }

        $this->callAfterCreating([$entity]);

        return $entity;
    }

    public function __get(string $name): array
    {
        return match ($name) {
            'data' => $this->raw([$this, 'definition']),
            default => throw new FactoryException('Undefined magic property.')
        };
    }

    private function storeEntities(array $entities): void
    {
        $container = ContainerScope::getContainer();
        if ($container === null) {
            throw new OutsideScopeException(\sprintf(
                'The container is not available. Make sure [%s] method is running in the ContainerScope.',
                __METHOD__
            ));
        }

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        foreach ($entities as $entity) {
            $em->persist($entity);
        }
        $em->run();
    }

    /** @internal */
    private function object(callable $definition): object|array
    {
        $this->entityFactory->define($this->entity(), $definition);

        return $this->entityFactory->of($this->entity())->times($this->amount)->make($this->replaces);
    }

    /** @internal */
    private function raw(callable $definition): array
    {
        $this->entityFactory->define($this->entity(), $definition);

        $data = $this->entityFactory->of($this->entity())->times($this->amount)->raw($this->replaces);

        return \array_is_list($data) ? $data[0] : $data;
    }

    /** @internal */
    private function callAfterCreating(array $entities)
    {
        foreach ($entities as $entity) {
            \array_map(static fn(callable $callable) => $callable($entity), $this->afterCreate);
        }
    }
}
