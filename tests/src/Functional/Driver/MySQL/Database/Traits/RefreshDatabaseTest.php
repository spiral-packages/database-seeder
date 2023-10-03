<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\MySQL\Database\Traits;

use Cycle\Database\Database;
use Spiral\DatabaseSeeder\Database\DatabaseState;
use Tests\Functional\Driver\Common\Database\Traits\RefreshDatabaseTestCase;

/**
 * @group driver
 * @group driver-mysql
 *
 * Tests in conventional database (MySQL)
 */
final class RefreshDatabaseTest extends RefreshDatabaseTestCase
{
    public const ENV = [
        'DEFAULT_DB' => 'mysql'
    ];

    public function testUsingInMemoryDatabase(): void
    {
        $this->assertFalse($this->usingInMemoryDatabase());
    }

    public function testRefreshTestDatabase(): void
    {
        $this->assertSame(0, $this->getContainer()->get(Database::class)->getDriver()->getTransactionLevel());

        $this->assertFalse(DatabaseState::$migrated);
        $this->refreshTestDatabase();
        $this->assertTrue(DatabaseState::$migrated);

        // the transaction is opened. It will be closed in the finalizer after executing a test
        $this->assertSame(1, $this->getContainer()->get(Database::class)->getDriver()->getTransactionLevel());

        $this->assertTable('comments')->assertExists();
        $this->assertTable('posts')->assertExists();
        $this->assertTable('users')->assertExists();
    }
}
