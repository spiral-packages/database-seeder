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
        protected readonly TestCase $testCase
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

    public function withoutScope(): self
    {
        $self = clone $this;
        $self->select->scope(null);

        return $self;
    }

    public function withScope(ScopeInterface $scope) : self
    {
        $self = clone $this;
        $self->select->scope($scope);

        return $self;
    }

    public function select(\Closure $closure) : self
    {
        $self = clone $this;
        $closure($self->select);

        return $self;
    }


    public function where(array $where): self
    {
        $self = clone $this;
        $this->select->where($where);

        return $self;
    }

    public function assertCount(int $total): void
    {
        $actual = $this->count();

        TestCase::assertSame(
            $total,
            $actual,
            \sprintf('Expected %s entities in the table, actual are %s.', $total, $actual),
        );
    }

    public function assertExists(): void
    {
        TestCase::assertTrue($this->count() > 0, \sprintf('Entity [%s] not found.', $this->entity));
    }

    public function assertMissing(): void
    {
        TestCase::assertSame(0, $this->count(), \sprintf('Entity [%s] found.',  $this->entity));
    }

    public function assertEmpty(): void
    {
        $this->assertCount(0);
    }

    public function count(): int
    {
        return $this->select->count();
    }
}
