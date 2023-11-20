<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database;

use Cycle\Database\ColumnInterface;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\Query\SelectQuery;
use Spiral\Testing\TestCase;

class TableAssertion
{
    protected DatabaseInterface $database;
    protected SelectQuery $select;

    /**
     * @param non-empty-string $table
     * @throws \Throwable
     */
    public function __construct(
        protected readonly string $table,
        protected readonly TestCase $testCase,
    ) {
        $this->database = $this->testCase->getContainer()->get(DatabaseInterface::class);
        /** @psalm-suppress InternalMethod */
        $this->select = $this->database->select()->from($this->table);
    }

    /**
     * Assert that table exists.
     */
    public function assertExists(): self
    {
        TestCase::assertTrue(
            $this->database->hasTable($this->table),
            \sprintf('Table [%s] does not exist.', $this->table),
        );

        return $this;
    }

    /**
     * Assert that the table is not present in the database.
     */
    public function assertMissing(): self
    {
        TestCase::assertFalse(
            $this->database->hasTable($this->table),
            \sprintf('Table [%s] exists.', $this->table),
        );

        return $this;
    }

    /**
     * Assert that column in the table exists.
     */
    public function assertColumnExists(string $column): self
    {
        TestCase::assertTrue(
            $this->database->table($this->table)->hasColumn($column),
            \sprintf('Column [%s] does not exist.', $column),
        );

        return $this;
    }

    /**
     * Assert that column in the table has specific settings.
     */
    public function assertColumnSame(
        string $column,
        string $type,
        ?string $internalType = null,
        ?int $size = null,
        ?int $precision = null,
        ?int $scale = null,
        ?bool $nullable = null,
        ?bool $hasDefaultValue = null,
        mixed $defaultValue = null,
    ): self {
        $this->assertColumnExists($column);
        /** @var ColumnInterface $column */
        $column = \array_filter(
            $this->database->table($this->table)->getColumns(),
            static fn(ColumnInterface $c) => $c->getName() === $column,
        )[0];

        TestCase::assertSame($type, $column->getType());
        if ($internalType !== null) {
            TestCase::assertSame(
                $internalType,
                $column->getInternalType(),
                \sprintf(
                    'Internal type for column [%s] is not equal to expected [%s].',
                    $column->getName(),
                    $internalType,
                ),
            );
        }

        if ($size !== null) {
            TestCase::assertSame(
                $size,
                $column->getSize(),
                \sprintf(
                    'Size for column [%s] is not equal to expected [%s].',
                    $column->getName(),
                    $size,
                ),
            );
        }

        if ($precision !== null) {
            TestCase::assertSame(
                $precision,
                $column->getPrecision(),
                \sprintf(
                    'Precision for column [%s] is not equal to expected [%s].',
                    $column->getName(),
                    $precision,
                ),
            );
        }

        if ($scale !== null) {
            TestCase::assertSame(
                $scale,
                $column->getScale(),
                \sprintf(
                    'Scale for column [%s] is not equal to expected [%s].',
                    $column->getName(),
                    $scale,
                ),
            );
        }

        if ($nullable !== null) {
            TestCase::assertSame(
                $nullable,
                $column->isNullable(),
                \sprintf(
                    'Nullable for column [%s] is not equal to expected.',
                    $column->getName(),
                ),
            );
        }

        if ($hasDefaultValue !== null) {
            TestCase::assertSame(
                $hasDefaultValue,
                $column->hasDefaultValue(),
                \sprintf(
                    'Has default value for column [%s] is not equal to expected.',
                    $column->getName(),
                ),
            );
        }

        if ($column->hasDefaultValue()) {
            TestCase::assertSame(
                $defaultValue,
                $column->getDefaultValue(),
                \sprintf(
                    'Default value for column [%s] is not equal to expected.',
                    $column->getName(),
                ),
            );
        }

        return $this;
    }

    /**
     * Assert if table has index related to set of provided columns. Columns order does matter!
     */
    public function assertIndexPresent(array $columns): self
    {
        TestCase::assertTrue(
            $this->database->table($this->table)->hasIndex($columns),
            \sprintf('Index for columns [%s] does not exist.', \implode(', ', $columns)),
        );

        return $this;
    }

    /**
     * Assert if table has foreign key related to table column.
     */
    public function assertForeignKeyPresent(array $columns): self
    {
        TestCase::assertTrue(
            $this->database->table($this->table)->hasForeignKey($columns),
            \sprintf('Foreign key for columns [%s] does not exist.', \implode(', ', $columns)),
        );

        return $this;
    }

    /**
     * Assert that column in the table is primary key.
     */
    public function assertPrimaryKeyExists(string ...$columns): self
    {
        $pks = $this->database->table($this->table)->getPrimaryKeys();

        foreach ($columns as $column) {
            TestCase::assertContains($column, $pks, \sprintf('Primary key [%s] does not exist.', $column));
        }

        return $this;
    }

    /**
     * Assert that column in the table does not exist.
     */
    public function assertColumnMissing(string $column): self
    {
        TestCase::assertFalse(
            $this->database->table($this->table)->hasColumn($column),
            \sprintf('Column [%s] exists.', $column),
        );

        return $this;
    }

    /**
     * Assert that at least one record is present in the table for the current query.
     */
    public function assertRecordExists(): void
    {
        TestCase::assertTrue(
            $this->countRecords() > 0,
            \sprintf('Record not found in the table [%s].', $this->table),
        );
    }

    /**
     * Use query builder for the next query.
     *
     * @param \Closure(SelectQuery):void $closure
     */
    public function select(\Closure $closure): self
    {
        $self = clone $this;
        $closure($self->select);

        return $self;
    }

    /**
     * Add where condition for the next query.
     */
    public function where(array $where): self
    {
        $self = clone $this;
        $self->select->where($where);

        return $self;
    }

    /**
     * Assert that the number of records in the table for the current query is equal to the expected number.
     */
    public function assertCountRecords(int $total): void
    {
        $actual = $this->countRecords();

        TestCase::assertSame(
            $total,
            $actual,
            \sprintf('Expected %s records in the table [%s], actual are %s.', $total, $this->table, $actual),
        );
    }

    /**
     * Assert that no records are present in the table for the current query.
     */
    public function assertEmpty(): void
    {
        $this->assertCountRecords(0);
    }

    /**
     * Count records in the table for the current query.
     */
    public function countRecords(): int
    {
        /** @psalm-suppress InternalMethod */
        return $this->select->count();
    }

    public function __clone()
    {
        $this->select = clone $this->select;
    }
}
