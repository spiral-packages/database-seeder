<?php

declare(strict_types=1);

namespace Tests\Functional\Database\Traits;

use Cycle\Database\Config\MySQL\TcpConnectionConfig;
use Cycle\Database\Config\MySQLDriverConfig;
use Spiral\DatabaseSeeder\Database\Exception\RefreshDatabaseException;
use Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase;
use Tests\Functional\TestCase;

final class RefreshDatabaseTest extends TestCase
{
    use RefreshDatabase;

    // disabling auto executing DB traits
    protected function setUp(): void
    {
        $this->refreshApp();
    }

    public function testUsingInMemoryDatabaseSuccess(): void
    {
        // default config with in memory db
        $this->assertTrue($this->usingInMemoryDatabase());
    }

    public function testUsingInMemoryDatabaseFalse(): void
    {
        $this->updateConfig('database.drivers.testing', new MySQLDriverConfig(new TcpConnectionConfig('')));

        $this->assertFalse($this->usingInMemoryDatabase());
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

    public function testRefreshInMemoryDatabaseMigrationsNotConfigured(): void
    {
        $this->updateConfig('migration.directory', null);

        $this->expectException(RefreshDatabaseException::class);
        $this->expectExceptionMessage(
            'Please, configure migrations in your test application to use auto database refreshing.'
        );
        $this->refreshInMemoryDatabase();
    }

    public function testRefreshInMemoryDatabaseMigrationsSafeIsFalse(): void
    {
        $this->updateConfig('migration.safe', false);

        $this->expectException(RefreshDatabaseException::class);
        $this->expectExceptionMessage(
            'The `safe` parameter in the test application migrations configuration must be set to true.'
        );
        $this->refreshInMemoryDatabase();
    }
}
