<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseProviderInterface;
use Cycle\Database\Driver\DriverInterface;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Spiral\DatabaseSeeder\Database\Cleaner;

trait Helper
{
    private ?Cleaner $cleaner = null;

    public function getDatabaseCleaner(): Cleaner
    {
        if ($this->cleaner === null) {
            $this->cleaner = new Cleaner($this->getCurrentDatabaseProvider());
        }

        return $this->cleaner;
    }

    public function getCurrentDatabase(): DatabaseInterface
    {
        return $this->getContainer()->get(DatabaseInterface::class);
    }

    public function getCurrentDatabaseDriver(): DriverInterface
    {
        return $this->getCurrentDatabase()->getDriver();
    }

    public function getCurrentDatabaseProvider(): DatabaseProviderInterface
    {
        return $this->getContainer()->get(DatabaseProviderInterface::class);
    }

    public function getOrm(): ORMInterface
    {
        return $this->getContainer()->get(ORMInterface::class);
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->getContainer()->get(EntityManagerInterface::class);
    }

    public function detachEntityFromIdentityMap(object $entity): void
    {
        $this->getOrm()->getHeap()->detach($entity);
    }

    public function cleanIdentityMap(): void
    {
        $this->getOrm()->getHeap()->clean();
    }

    public function getRepositoryFor(object|string $entity): RepositoryInterface
    {
        return $this->getOrm()->getRepository($entity);
    }

    public function persist(object $entity): void
    {
        $this->getEntityManager()->persist($entity)->run();
    }
}
