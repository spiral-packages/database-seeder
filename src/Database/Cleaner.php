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

    public function truncateTable(string $table, ?string $database = null, bool $foreignKeyConstraints = true): void
    {
        $db = $this->provider->database($database);

        if (!$foreignKeyConstraints) {
            $this->disableForeignKeyConstraints($database);
        }

        $db->getDriver()->getSchemaHandler()->eraseTable($db->table($table));

        if (!$foreignKeyConstraints) {
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

            $this->truncateTable($table->getFullName(), database: $database, foreignKeyConstraints: false);
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
