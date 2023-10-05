<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database;

use Cycle\Database\DatabaseProviderInterface;

class Cleaner
{
    public function __construct(
        protected readonly DatabaseProviderInterface $provider
    ) {
    }

    public function truncateTable(
        string $table,
        ?string $database = null,
        bool $disableForeignKeyConstraints = true
    ): void {
        $db = $this->provider->database($database);

        if ($disableForeignKeyConstraints) {
            $this->disableForeignKeyConstraints($database);
        }

        $db->getDriver()->getSchemaHandler()->eraseTable($db->table($table)->getSchema());

        if ($disableForeignKeyConstraints) {
            $this->enableForeignKeyConstraints($database);
        }
    }

    public function dropTable(string $table, ?string $database = null, bool $disableForeignKeyConstraints = true): void
    {
        $db = $this->provider->database($database);

        if ($disableForeignKeyConstraints) {
            $this->disableForeignKeyConstraints($database);
        }

        $db->getDriver()->getSchemaHandler()->dropTable($db->table($table)->getSchema());

        if ($disableForeignKeyConstraints) {
            $this->enableForeignKeyConstraints($database);
        }
    }

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

            $this->truncateTable($table->getFullName(), database: $database, disableForeignKeyConstraints: true);
        }
    }

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

            $this->dropTable($table->getFullName(), database: $database, disableForeignKeyConstraints: true);
        }
    }

    public function disableForeignKeyConstraints(?string $database = null): void
    {
        $db = $this->provider->database($database);

        $db->getDriver()->getSchemaHandler()->disableForeignKeyConstraints();
    }

    public function enableForeignKeyConstraints(?string $database = null): void
    {
        $db = $this->provider->database($database);

        $db->getDriver()->getSchemaHandler()->enableForeignKeyConstraints();
    }
}
