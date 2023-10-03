<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\SQLite\Database\Traits;

use Tests\Functional\Driver\Common\Database\Traits\RefreshDatabaseTestCase;

/**
 * @group driver
 * @group driver-sqlite
 *
 * Tests in-memory database (SQLite with in-memory connection)
 */
final class RefreshDatabaseTest extends RefreshDatabaseTestCase
{
    public function testUsingInMemoryDatabase(): void
    {
        $this->assertTrue($this->usingInMemoryDatabase());
    }

    public function testRefreshInMemoryDatabase(): void
    {
        // by default, in memory db empty
        $this->assertTable('comments')->assertMissing();
        $this->assertTable('posts')->assertMissing();
        $this->assertTable('users')->assertMissing();

        $this->refreshInMemoryDatabase();

        $this->assertTable('comments')->assertExists();
        $this->assertTable('posts')->assertExists();
        $this->assertTable('users')->assertExists();
    }
}
