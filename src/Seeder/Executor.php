<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

use Cycle\ORM\EntityManagerInterface;

class Executor implements ExecutorInterface
{
    private array $afterSeed = [];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
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
            $this->entityManager->persist($entity);
        }
    }

    private function callAfterSeed(SeederInterface $seeder): void
    {
        \array_map(static fn(callable $callable) => $callable($seeder), $this->afterSeed);
    }
}
