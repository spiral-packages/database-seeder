<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\Database\Database;
use Cycle\ORM\ORM;
use Cycle\ORM\Select\Repository;

trait DatabaseAsserts
{
    /** @psalm-param non-empty-string $table */
    public function assertTableExists(string $table): void
    {
        static::assertTrue(
            $this->getContainer()->get(Database::class)->hasTable($table),
            \sprintf('Table [%s] does not exist.', $table)
        );
    }

    /** @psalm-param non-empty-string $table */
    public function assertTableIsNotExists(string $table): void
    {
        static::assertFalse(
            $this->getContainer()->get(Database::class)->hasTable($table),
            \sprintf('Table [%s] exists.', $table)
        );
    }

    /** @psalm-param non-empty-string $table */
    public function assertTableCount(string $table, int $count): void
    {
        $actual = $this->getContainer()->get(Database::class)->table($table)->count();

        static::assertSame(
            $count,
            $actual,
            \sprintf('Expected %s records in the table [%s], actual are %s.', $count, $table, $actual)
        );
    }

    /** @psalm-param non-empty-string $table */
    public function assertTableHas(string $table, array $where = []): void
    {
        $select = $this->getContainer()->get(Database::class)->table($table)->select();

        if ($where !== []) {
            $select->where($where);
        }

        static::assertTrue($select->count() >= 0, \sprintf('Record not found in the table [%s].', $table));
    }

    /** @param class-string $entity */
    public function assertEntitiesCount(string $entity, int $count): void
    {
        /** @var Repository $repository */
        $repository = $this->getContainer()->get(ORM::class)->getRepository($entity);
        $actual = $repository->select()->count();

        static::assertSame(
            $count,
            $actual,
            \sprintf('Expected %s entities in the table, actual are %s.', $count, $actual)
        );
    }

    /** @param class-string $entity */
    public function assertTableHasEntity(string $entity, array $where = []): void
    {
        /** @var Repository $repository */
        $repository = $this->getContainer()->get(ORM::class)->getRepository($entity);
        $select = $repository->select();

        if ($where !== []) {
            $select->where($where);
        }

        static::assertTrue($select->count() >= 0, \sprintf('Entity [%s] not found.', $entity));
    }
}
