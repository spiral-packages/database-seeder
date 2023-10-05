<?php

declare(strict_types=1);

namespace Tests\Functional\Database\Strategy;

use Spiral\DatabaseSeeder\Database\Strategy\MigrationStrategy;
use Spiral\DatabaseSeeder\Database\Traits\DatabaseAsserts;
use Tests\Functional\TestCase;

final class MigrationStrategyTest extends TestCase
{
    use DatabaseAsserts;

    public function testMigrateWithCreatingMigrations(): void
    {
        $this->assertTable('comments')->assertMissing();
        $this->assertTable('posts')->assertMissing();
        $this->assertTable('users')->assertMissing();
        $this->assertTable('composite_pk')->assertMissing();

        $strategy = new MigrationStrategy($this, true);
        $strategy->migrate();

        $this->assertTable('comments')->assertExists();
        $this->assertTable('comments')->assertColumnExists('id');
        $this->assertTable('comments')->assertColumnExists('author_id');
        $this->assertTable('comments')->assertColumnExists('post_id');
        $this->assertTable('comments')->assertColumnExists('posted_at');
        $this->assertTable('comments')->assertEmpty();

        $this->assertTable('posts')->assertExists();
        $this->assertTable('posts')->assertColumnExists('id');
        $this->assertTable('posts')->assertColumnExists('content');
        $this->assertTable('posts')->assertColumnExists('author_id');
        $this->assertTable('posts')->assertColumnExists('published_at');
        $this->assertTable('posts')->assertEmpty();

        $this->assertTable('users')->assertExists();
        $this->assertTable('users')->assertColumnExists('id');
        $this->assertTable('users')->assertColumnExists('birthday');
        $this->assertTable('users')->assertColumnExists('city');
        $this->assertTable('users')->assertColumnExists('age');
        $this->assertTable('users')->assertColumnExists('active');
        $this->assertTable('users')->assertEmpty();

        $this->assertTable('composite_pk')->assertExists();
        $this->assertTable('composite_pk')->assertColumnExists('id');
        $this->assertTable('composite_pk')->assertColumnExists('other_id');
        $this->assertTable('composite_pk')->assertColumnExists('content');
        $this->assertTable('composite_pk')->assertEmpty();

        $strategy->rollback();

        $this->assertTable('comments')->assertMissing();
        $this->assertTable('posts')->assertMissing();
        $this->assertTable('users')->assertMissing();
        $this->assertTable('composite_pk')->assertMissing();
    }

    public function testMigrateWithoutCreatingMigrations(): void
    {
       $baseDir = \dirname(__DIR__, 4) . '/app/database/';

       \copy(
           $baseDir . 'fixtures/20231005.212449_0_0_mysql_create_posts.php',
           $baseDir . 'migrations/20231005.212449_0_0_mysql_create_posts.php'
       );

        $this->assertTable('comments')->assertMissing();
        $this->assertTable('posts')->assertMissing();
        $this->assertTable('users')->assertMissing();
        $this->assertTable('composite_pk')->assertMissing();

        $strategy = new MigrationStrategy($this, false);
        $strategy->migrate();

        $this->assertTable('posts')->assertExists();
        $this->assertTable('posts')->assertColumnExists('id');
        $this->assertTable('posts')->assertColumnExists('content');
        $this->assertTable('posts')->assertColumnExists('author_id');
        $this->assertTable('posts')->assertColumnExists('published_at');
        $this->assertTable('posts')->assertEmpty();

        $this->assertTable('comments')->assertMissing();
        $this->assertTable('users')->assertMissing();
        $this->assertTable('composite_pk')->assertMissing();

        $strategy->rollback();
        \unlink($baseDir . 'migrations/20231005.212449_0_0_mysql_create_posts.php');

        $this->assertTable('comments')->assertMissing();
        $this->assertTable('posts')->assertMissing();
        $this->assertTable('users')->assertMissing();
        $this->assertTable('composite_pk')->assertMissing();
    }
}
