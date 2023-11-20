<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Spiral\DatabaseSeeder\Database\EntityAssertion;
use Spiral\DatabaseSeeder\Database\TableAssertion;

trait DatabaseAsserts
{
    /**
     * Build entity assertion.
     *
     * @param class-string|object $entity
     */
    public function assertEntity(string|object $entity): EntityAssertion
    {
        if (\is_object($entity)) {
            $entity = $entity::class;
        }

        return new EntityAssertion($entity, $this);
    }

    /**
     * Build table assertion.
     *
     * @param non-empty-string $table
     */
    public function assertTable(string $table): TableAssertion
    {
        return new TableAssertion($table, $this);
    }
}
