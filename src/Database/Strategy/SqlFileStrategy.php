<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Strategy;

use Cycle\Database\DatabaseProviderInterface;
use Spiral\DatabaseSeeder\Database\DatabaseState;
use Spiral\DatabaseSeeder\Database\Exception\DatabaseException;

class SqlFileStrategy
{
    public function __construct(
        protected readonly string $preparePath,
        protected readonly DatabaseProviderInterface $provider,
        protected ?string $dropPath = null,
        protected ?string $database = null,
    ) {
    }

    public function execute(): void
    {
        if (DatabaseState::$migrated) {
            return;
        }

        $sql = \file_get_contents($this->preparePath);

        if (!\is_string($sql)) {
            throw new DatabaseException('Could not read SQL file.');
        }
        \assert(!empty($sql));

        $this->provider->database($this->database)->execute($sql);

        DatabaseState::$migrated = true;
    }

    public function drop(): void
    {
        if (empty($this->dropPath)) {
            return;
        }

        $sql = \file_get_contents($this->dropPath);

        if (!\is_string($sql)) {
            throw new DatabaseException('Could not read SQL file.');
        }
        \assert(!empty($sql));

        $this->provider->database($this->database)->execute($sql);

        DatabaseState::$migrated = false;
    }
}
