<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Spiral\DatabaseSeeder\Database\EntityAssertion;
use Spiral\DatabaseSeeder\Database\TableAssertion;

trait DatabaseAsserts
{
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
}
