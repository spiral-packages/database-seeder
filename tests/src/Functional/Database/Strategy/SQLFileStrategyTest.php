<?php

declare(strict_types=1);

namespace Tests\Functional\Database\Strategy;

use Spiral\DatabaseSeeder\Database\Strategy\SqlFileStrategy;
use Spiral\DatabaseSeeder\Database\Traits\DatabaseAsserts;
use Tests\Functional\TestCase;

final class SQLFileStrategyTest extends TestCase
{
    use DatabaseAsserts;

    public function testExecute(): void
    {
        $this->assertTable('users')->assertMissing();

        $strategy = new SqlFileStrategy(
            \dirname(__DIR__, 4) . '/app/database/sql/execute.sql',
            $this->getCurrentDatabaseProvider()
        );
        $strategy->execute();

        $this->assertTable('users')->assertExists();
        $this->assertTable('users')->assertCountRecords(5);

        $this->getDatabaseCleaner()->dropTable('users');
    }

    public function testExecuteAndDrop(): void
    {
        $this->assertTable('users')->assertMissing();

        $strategy = new SqlFileStrategy(
            \dirname(__DIR__, 4) . '/app/database/sql/execute.sql',
            $this->getCurrentDatabaseProvider(),
            \dirname(__DIR__, 4) . '/app/database/sql/drop.sql',
        );
        $strategy->execute();

        $this->assertTable('users')->assertExists();
        $this->assertTable('users')->assertCountRecords(5);

        $strategy->drop();

        $this->assertTable('users')->assertMissing();
    }
}
