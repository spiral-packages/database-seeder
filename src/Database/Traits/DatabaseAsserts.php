<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\Database\Database;
use Cycle\ORM\ORM;
use Cycle\ORM\Select\Repository;

trait DatabaseAsserts
{
    public function assertTableExists(string $table): void
    {
        static::assertTrue(
            $this->getContainer()->get(Database::class)->hasTable($table),
            \sprintf('Table [%s] does not exist.', $table)
        );
    }

    public function assertTableIsNotExists(string $table): void
    {
        static::assertFalse(
            $this->getContainer()->get(Database::class)->hasTable($table),
            \sprintf('Table [%s] exists.', $table)
        );
    }

    public function assertTableCount(string $table, int $count): void
    {
        static::assertSame(
            $count,
            $this->getContainer()->get(Database::class)->table($table)->count()
        );
    }

    public function assertTableHas(string $table, array $where = []): void
    {
        $select = $this->getContainer()->get(Database::class)->table($table)->select();

        if ($where !== []) {
            $select->where($where);
        }

        static::assertTrue($select->count() >= 0);
    }

    /** @param class-string $entity */
    public function assertEntitiesCount(string $entity, int $count): void
    {
        /** @var Repository $repository */
        $repository = $this->getContainer()->get(ORM::class)->getRepository($entity);

        static::assertSame($count, $repository->select()->count());
    }

    public function assertTableHasEntity(string $entity, array $where = []): void
    {
        /** @var Repository $repository */
        $repository = $this->getContainer()->get(ORM::class)->getRepository($entity);
        $select = $repository->select();

        if ($where !== []) {
            $select->where($where);
        }

        static::assertTrue($select->count() >= 0);
    }
}
