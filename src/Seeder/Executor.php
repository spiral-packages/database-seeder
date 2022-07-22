<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

use Cycle\Database\Injection\Parameter;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\ORM;
use Cycle\ORM\SchemaInterface;

class Executor implements ExecutorInterface
{
    private array $afterSeed = [];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ORM $orm
    ) {
    }

    public function afterSeed(callable $callback): self
    {
        $this->afterSeed[] = $callback;

        return $this;
    }

    /**
     * @psalm-param iterable<SeederInterface> $seeders
     */
    public function execute(iterable $seeders): void
    {
        foreach ($seeders as $seeder) {
            $this->seed($seeder);
            $this->callAfterSeed($seeder);
        }

        $this->entityManager->run();
    }

    private function seed(SeederInterface $seeder): void
    {
        foreach ($seeder->run() as $entity) {
            if ($this->isNotExists($entity)) {
                $this->entityManager->persist($entity);
            }
        }
    }

    private function callAfterSeed(SeederInterface $seeder): void
    {
        \array_map(static fn(callable $callable) => $callable($seeder), $this->afterSeed);
    }

    private function isNotExists(object $entity): bool
    {
        $keys = $this->orm->getSchema()->define($this->orm->resolveRole($entity), SchemaInterface::PRIMARY_KEY);

        $values = [];
        foreach ($keys as $key) {
            $ref = new \ReflectionProperty($entity, $key);
            if ($ref->isInitialized($entity)) {
                $values[$key] = $ref->getValue($entity);
            }
        }

        if ($values === []) {
            return true;
        }

        return $this->orm->getRepository($entity)->findByPK(new Parameter($values)) === null;
    }
}
