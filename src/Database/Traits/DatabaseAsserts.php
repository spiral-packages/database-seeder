<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select\Repository;
use Spiral\DatabaseSeeder\Database\EntityAssertion;
use Spiral\DatabaseSeeder\Database\TableAssertion;

trait DatabaseAsserts
{
    /**
     * @psalm-param non-empty-string $table
     *
     * @deprecated Use assertTable()->assertExists() instead.
     */
    public function assertTableExists(string $table): void
    {
        $this->assertTable($table)->assertExists();
    }

    /**
     * @psalm-param non-empty-string $table
     *
     * @deprecated Use assertTable()->assertMissing() instead.
     */
    public function assertTableIsNotExists(string $table): void
    {
        $this->assertTable($table)->assertMissing();
    }

    /**
     * @psalm-param non-empty-string $table
     *
     * @deprecated Use assertTable()->assertCountRecords() instead.
     */
    public function assertTableCount(string $table, int $count): void
    {
        $this->assertTable($table)->assertCountRecords($count);
    }

    /**
     * @psalm-param non-empty-string $table
     *
     * @deprecated Use assertTable()->where()->assertRecordExists() instead.
     */
    public function assertTableHas(string $table, array $where = []): void
    {
        $this->assertTable($table)->where($where)->assertRecordExists();
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

    /**
     * @param non-empty-string $table
     */
    public function assertTable(string $table): TableAssertion
    {
        return new TableAssertion($table, $this);
    }

    private function getOrm(): ORMInterface
    {
        return $this->getContainer()->get(ORMInterface::class);
    }
}
