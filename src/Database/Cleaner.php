<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database;

use Cycle\Database\DatabaseProviderInterface;

class Cleaner
{
    public function __construct(
        protected readonly DatabaseProviderInterface $provider,
    ) {
    }

    /**
     * @param non-empty-string $table
     * @param non-empty-string|null $database
     */
    public function truncateTable(
        string $table,
        ?string $database = null,
        bool $disableForeignKeyConstraints = true,
    ): void {
        $db = $this->provider->database($database);

        if ($disableForeignKeyConstraints) {
            $this->disableForeignKeyConstraints($database);
        }

        /**
         * @psalm-suppress UndefinedInterfaceMethod
         */
        $db->getDriver()->getSchemaHandler()->eraseTable($db->table($table)->getSchema());

        if ($disableForeignKeyConstraints) {
            $this->enableForeignKeyConstraints($database);
        }
    }

    /**
     * @param non-empty-string $table
     * @param non-empty-string|null $database
     */
    public function dropTable(string $table, ?string $database = null, bool $disableForeignKeyConstraints = true): void
    {
        $db = $this->provider->database($database);

        if ($disableForeignKeyConstraints) {
            $this->disableForeignKeyConstraints($database);
        }

        /**
         * @psalm-suppress UndefinedInterfaceMethod
         */
        $db->getDriver()->getSchemaHandler()->dropTable($db->table($table)->getSchema());

        if ($disableForeignKeyConstraints) {
            $this->enableForeignKeyConstraints($database);
        }
    }

    /**
     * @param non-empty-string|null $database
     */
    public function refreshDb(?string $database = null, array $except = []): void
    {
        $db = $this->provider->database($database);

        foreach ($db->getTables() as $table) {
            $name = \explode('.', $table->getFullName(), 2);

            if (\count($name) > 1) {
                $tableName = $name[1];
            } else {
                $tableName = $name[0];
            }

            if (\in_array($tableName, $except, true)) {
                continue;
            }

            $fullName = $table->getFullName();
            \assert(!empty($fullName));

            $this->truncateTable($fullName, database: $database, disableForeignKeyConstraints: true);
        }
    }

    /**
     * @param non-empty-string|null $database
     */
    public function dropTables(?string $database = null, array $except = []): void
    {
        $db = $this->provider->database($database);

        foreach ($db->getTables() as $table) {
            $name = \explode('.', $table->getFullName(), 2);

            if (\count($name) > 1) {
                $tableName = $name[1];
            } else {
                $tableName = $name[0];
            }

            if (\in_array($tableName, $except, true)) {
                continue;
            }

            $fullName = $table->getFullName();
            \assert(!empty($fullName));

            $this->dropTable($fullName, database: $database, disableForeignKeyConstraints: true);
        }
    }

    public function disableForeignKeyConstraints(?string $database = null): void
    {
        $db = $this->provider->database($database);

        /**
         * @psalm-suppress UndefinedInterfaceMethod
         */
        $db->getDriver()->getSchemaHandler()->disableForeignKeyConstraints();
    }

    public function enableForeignKeyConstraints(?string $database = null): void
    {
        $db = $this->provider->database($database);

        /**
         * @psalm-suppress UndefinedInterfaceMethod
         */
        $db->getDriver()->getSchemaHandler()->enableForeignKeyConstraints();
    }
}
