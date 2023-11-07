<?php

declare(strict_types=1);

namespace Tests\Functional\Database\Strategy;

use Cycle\Database\DatabaseInterface;
use Spiral\DatabaseSeeder\Database\Strategy\MigrationStrategy;
use Spiral\DatabaseSeeder\Database\Strategy\RefreshStrategy;
use Spiral\DatabaseSeeder\Database\Traits\DatabaseAsserts;
use Tests\Database\Factory\PostFactory;
use Tests\Database\Factory\UserFactory;
use Tests\Functional\TestCase;

final class RefreshStrategyTest extends TestCase
{
    use DatabaseAsserts;

    public function testRefreshStrategy(): void
    {
        $db = $this->getCurrentDatabase();
        $schema = $db->table('users')->getSchema();
        $schema->primary('id');
        $schema->datetime('birthday')->nullable();
        $schema->string('city')->nullable();
        $schema->string('first_name');
        $schema->string('last_name');
        $schema->integer('age');
        $schema->boolean('active');
        $schema->float('some_float_val');
        $schema->boolean('admin');

        $schema->save();

        UserFactory::new()->times(5)->create();

        $this->assertTable('users')->assertCountRecords(5);

        $strategy = new RefreshStrategy($this->getDatabaseCleaner());
        $strategy->refresh();

        $this->assertTable('users')->assertEmpty();

        $this->getDatabaseCleaner()->dropTable('users');
    }

    public function testRefreshStrategyWithExceptTable(): void
    {
        $migrationStrategy = new MigrationStrategy($this, true);
        $migrationStrategy->migrate();

        UserFactory::new()->times(5)->create();
        PostFactory::new()->times(1)->create();

        $this->assertTable('users')->assertCountRecords(9);
        $this->assertTable('posts')->assertCountRecords(1);
        $this->assertTable('comments')->assertCountRecords(3);

        $strategy = new RefreshStrategy($this->getDatabaseCleaner(), except: ['comments', 'migrations']);
        $strategy->refresh();

        $this->assertTable('users')->assertEmpty();
        $this->assertTable('posts')->assertEmpty();
        $this->assertTable('comments')->assertCountRecords(3);

        $migrationStrategy->rollback();

        $this->assertTable('users')->assertMissing();
        $this->assertTable('posts')->assertMissing();
        $this->assertTable('comments')->assertMissing();
    }
}
