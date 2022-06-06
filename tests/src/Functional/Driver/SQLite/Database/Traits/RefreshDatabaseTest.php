<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\SQLite\Database\Traits;

use Tests\Functional\Driver\Common\Database\Traits\RefreshDatabaseTest as CommonRefreshDatabaseTest;

/**
 * @group driver
 * @group driver-sqlite
 *
 * Tests in-memory database (SQLite with in-memory connection)
 */
final class RefreshDatabaseTest extends CommonRefreshDatabaseTest
{
    public function testUsingInMemoryDatabase(): void
    {
        $this->assertTrue($this->usingInMemoryDatabase());
    }

    public function testRefreshInMemoryDatabase(): void
    {
        // by default, in memory db empty
        $this->assertTableIsNotExists('comments');
        $this->assertTableIsNotExists('posts');
        $this->assertTableIsNotExists('users');

        $this->refreshInMemoryDatabase();

        $this->assertTableExists('comments');
        $this->assertTableExists('posts');
        $this->assertTableExists('users');
    }
}
