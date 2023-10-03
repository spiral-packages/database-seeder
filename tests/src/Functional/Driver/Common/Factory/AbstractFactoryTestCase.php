<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\Common\Factory;

use Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase;
use Tests\App\Database\Post;
use Tests\Database\Factory\PostFactory;
use Tests\Database\Factory\UserFactory;
use Tests\Functional\TestCase;

abstract class AbstractFactoryTestCase extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): void
    {
        $users = UserFactory::new()->times(5)->create();

        $this->assertTable('users')->assertCountRecords(5);
        $this->assertTable('users')->where(['id' => $users[0]->id])->assertExists();
        $this->assertTable('users')->where(['id' => $users[1]->id])->assertExists();
        $this->assertTable('users')->where(['id' => $users[2]->id])->assertExists();
        $this->assertTable('users')->where(['id' => $users[3]->id])->assertExists();
        $this->assertTable('users')->where(['id' => $users[4]->id])->assertExists();
    }

    public function testCreateOne(): void
    {
        $user = UserFactory::new()->createOne();

        $this->assertTable('users')->assertCountRecords(1);
        $this->assertTable('users')->where(['id' => $user->id])->assertExists();
    }

    public function testAfterCreateCallback(): void
    {
        $post = PostFactory::new()->afterCreate(fn(Post $post) => $post->content = 'changed by callback')->createOne();

        $this->assertSame('changed by callback', $post->content);
    }
}
