<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository;
use Cycle\ORM\Select\ScopeInterface;
use Spiral\Testing\TestCase;

class EntityAssertion
{
    protected Select $select;

    /**
     * @param class-string $entity
     */
    public function __construct(
        protected readonly string $entity,
        protected readonly TestCase $testCase,
    ) {
        /**
         * @var ORMInterface $orm
         */
        $orm = $testCase->getContainer()->get(ORMInterface::class);

        /**
         * @var Repository $repository
         */
        $repository = $orm->getRepository($entity);

        $this->select = $repository->select();
    }

    /**
     * Disable scope for the next query.
     */
    public function withoutScope(): self
    {
        $self = clone $this;
        $self->select->scope(null);

        return $self;
    }

    /**
     * Set scope for the next query.
     */
    public function withScope(ScopeInterface $scope): self
    {
        $self = clone $this;
        $self->select->scope($scope);

        return $self;
    }

    /**
     * Use query builder for the next query.
     * @param \Closure(Select):void $closure
     */
    public function select(\Closure $closure): self
    {
        $self = clone $this;
        $closure($self->select);

        return $self;
    }

    public function where(array $where): self
    {
        $self = clone $this;
        $self->select->where($where);

        return $self;
    }

    /**
     * Assert that the number of entities in the table for the current query is equal to the expected number.
     */
    public function assertCount(int $total): void
    {
        $actual = $this->count();

        TestCase::assertSame(
            $total,
            $actual,
            \sprintf('Expected %s entities in the table, actual are %s.', $total, $actual),
        );
    }

    /**
     * Assert that at least one entity is present in the table for the current query.
     */
    public function assertExists(): void
    {
        TestCase::assertTrue($this->count() > 0, \sprintf('Entity [%s] not found.', $this->entity));
    }

    /**
     * Assert that no entities are present in the table for the current query.
     */
    public function assertMissing(): void
    {
        TestCase::assertSame(0, $this->count(), \sprintf('Entity [%s] found.', $this->entity));
    }

    /**
     * Assert that no entities are present in the table for the current query.
     */
    public function assertEmpty(): void
    {
        $this->assertCount(0);
    }

    /**
     * Count entities in the table for the current query.
     */
    public function count(): int
    {
        return $this->select->count();
    }

    public function __clone()
    {
        $this->select = clone $this->select;
    }
}
