<?php

declare(strict_types=1);

namespace Tests\Database\Seeder;

use Spiral\DatabaseSeeder\Attribute\Seeder;
use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;
use Tests\App\Database\Comment;
use Tests\App\Database\Post;
use Tests\App\Database\User;
use Tests\Database\Factory\PostFactory;
use Tests\Database\Factory\UserFactory;

#[Seeder]
class CommentSeeder extends AbstractSeeder
{
    public function run(): \Generator
    {
        /** @var Post $post */
        $post = PostFactory::new()->createOne();
        /** @var User $user */
        $user = UserFactory::new()->createOne();

        $comment = new Comment();
        $comment->post = $post;
        $comment->postedAt = new \DateTimeImmutable();
        $comment->text = 'foo';
        $comment->author = $user;

        yield $comment;
    }
}
