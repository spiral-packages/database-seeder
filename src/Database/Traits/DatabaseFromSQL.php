<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\Database\DatabaseProviderInterface;
use Spiral\DatabaseSeeder\Database\Strategy\SqlFileStrategy;

trait DatabaseFromSQL
{
    private ?SqlFileStrategy $sqlFileStrategy = null;

    public function prepareDatabase(): void
    {
        $this->beforePreparingDatabase();

        $this->getSqlFileStrategy()->execute();

        $this->afterPreparingDatabase();
    }

    public function dropDatabase(): void
    {
        $this->beforeDropDatabase();

        $this->getSqlFileStrategy()->drop();

        $this->afterDropDatabase();
    }

    protected function setUpDatabaseFromSQL(): void
    {
        $this->prepareDatabase();
    }

    protected function tearDownDatabaseFromSQL(): void
    {
        $this->dropDatabase();
    }

    protected function getSqlFileStrategy(): SqlFileStrategy
    {
        if ($this->sqlFileStrategy === null) {
            $this->sqlFileStrategy = new SqlFileStrategy(
                preparePath: $this->getPrepareSQLFilePath(),
                provider: $this->getContainer()->get(DatabaseProviderInterface::class),
                dropPath: $this->getDropSQLFilePath(),
            );
        }

        return $this->sqlFileStrategy;
    }

    /**
     * @return non-empty-string
     */
    abstract protected function getPrepareSQLFilePath(): string;

    protected function getDropSQLFilePath(): ?string
    {
        return null;
    }

    /**
     * Perform any work that should take place before the database has started creating from SQL.
     */
    protected function beforePreparingDatabase(): void
    {
        // ...
    }

    /**
     * Perform any work that should take place once the database has finished creating from SQL.
     */
    protected function afterPreparingDatabase(): void
    {
        // ...
    }

    /**
     * Perform any work that should take place before the database has started creating from SQL.
     */
    protected function beforeDropDatabase(): void
    {
        // ...
    }

    /**
     * Perform any work that should take place once the database has finished creating from SQL.
     */
    protected function afterDropDatabase(): void
    {
        // ...
    }
}
