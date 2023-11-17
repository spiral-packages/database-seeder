<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database;

use Cycle\Database\DatabaseInterface;
use Cycle\Database\Query\SelectQuery;
use Spiral\Testing\TestCase;

class TableAssertion
{
    protected DatabaseInterface $database;
    protected SelectQuery $select;

    /**
     * @param non-empty-string $table
     */
    public function __construct(
        protected readonly string $table,
        protected readonly TestCase $testCase
    ) {
        $this->database = $this->testCase->getContainer()->get(DatabaseInterface::class);
        $this->select = $this->database->select()->from($this->table);
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

    public function assertExists(): void
    {
        TestCase::assertTrue(
            $this->database->hasTable($this->table),
            \sprintf('Table [%s] does not exist.', $this->table),
        );
    }

    public function assertColumnExists(string $column): void
    {
        TestCase::assertTrue(
            $this->database->table($this->table)->hasColumn($column),
            \sprintf('Column [%s] does not exist.', $column),
        );
    }

    public function assertColumnMissing(string $column): void
    {
        TestCase::assertFalse(
            $this->database->table($this->table)->hasColumn($column),
            \sprintf('Column [%s] exists.', $column),
        );
    }

    public function assertRecordExists(): void
    {
        TestCase::assertTrue(
            $this->countRecords() > 0,
            \sprintf('Record not found in the table [%s].', $this->table)
        );
    }

    public function assertMissing(): void
    {
        TestCase::assertFalse(
            $this->database->hasTable($this->table),
            \sprintf('Table [%s] exists.', $this->table),
        );
    }

    public function assertCountRecords(int $total): void
    {
        $actual = $this->countRecords();

        TestCase::assertSame(
            $total,
            $actual,
            \sprintf('Expected %s records in the table [%s], actual are %s.', $total, $this->table, $actual),
        );
    }

    public function assertEmpty(): void
    {
        $this->assertCountRecords(0);
    }

    public function countRecords(): int
    {
        return $this->select->count();
    }

    public function __clone(): void
    {
        $this->select = clone $this->select;
    }
}
