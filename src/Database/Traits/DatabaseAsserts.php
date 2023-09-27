<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\Database\Database;
use Cycle\ORM\ORM;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select\Repository;
use Spiral\DatabaseSeeder\Database\EntityAssertion;

trait DatabaseAsserts
{
    /** @psalm-param non-empty-string $table */
    public function assertTableExists(string $table): void
    {
        static::assertTrue(
            $this->getDatabase()->hasTable($table),
            \sprintf('Table [%s] does not exist.', $table),
        );
    }

    /** @psalm-param non-empty-string $table */
    public function assertTableIsNotExists(string $table): void
    {
        static::assertFalse(
            $this->getDatabase()->hasTable($table),
            \sprintf('Table [%s] exists.', $table),
        );
    }

    /** @psalm-param non-empty-string $table */
    public function assertTableCount(string $table, int $count): void
    {
        $actual = $this->getDatabase()->table($table)->count();

        static::assertSame(
            $count,
            $actual,
            \sprintf('Expected %s records in the table [%s], actual are %s.', $count, $table, $actual),
        );
    }

    /** @psalm-param non-empty-string $table */
    public function assertTableHas(string $table, array $where = []): void
    {
        $select = $this->getDatabase()->table($table)->select();

        if ($where !== []) {
            $select->where($where);
        }

        static::assertTrue($select->count() > 0, \sprintf('Record not found in the table [%s].', $table));
    }

    /**
     * @param class-string $entity
     *
     * @deprecated Use assertEntity()->assertCount() instead.
     */
    public function assertEntitiesCount(string $entity, int $count, bool $withoutScope = false): void
    {
        $assert = $this->assertEntity($entity);
        if ($withoutScope) {
            $assert = $assert->withoutScope();
        }

        $assert->assertCount($count);
    }

    /**
     * @param class-string $entity
     *
     * @deprecated Use assertEntity()->where()->assertExists() instead.
     */
    public function assertTableHasEntity(string $entity, array $where = [], bool $withoutScope = false): void
    {
        $assert = $this->assertEntity($entity);
        if ($withoutScope) {
            $assert = $assert->withoutScope();
        }

        $assert->where($where)->assertExists();
    }

    /**
     * @param class-string $entity
     *
     * @deprecated Use assertEntity()->where()->assertMissing() instead.
     */
    public function assertTableMissingEntity(string $entity, array $where = [], bool $withoutScope = false): void
    {
        $assert = $this->assertEntity($entity);
        if ($withoutScope) {
            $assert = $assert->withoutScope();
        }

        $assert->where($where)->assertMissing();
    }

    /**
     * @param class-string $entity
     *
     * @deprecated Use assertEntity()->where()->count() instead.
     */
    public function countEntityRecords(string $entity, array $where = [], bool $withoutScope = false): int
    {
        /** @var Repository $repository */
        $repository = $this->getOrm()->getRepository($entity);
        $select = $repository->select();

        if ($withoutScope) {
            $select->scope(null);
        }

        if ($where !== []) {
            $select->where($where);
        }

        return $select->count();
    }

    /**
     * @param class-string $entity
     */
    public function assertEntity(string $entity): EntityAssertion
    {
        return new EntityAssertion($entity, $this);
    }

    private function getOrm(): ORMInterface
    {
        return $this->getContainer()->get(ORM::class);
    }

    private function getDatabase(): Database
    {
        return $this->getContainer()->get(Database::class);
    }
}
