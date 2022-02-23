<?php

declare(strict_types=1);

namespace Tests\Fixture\Seeder;

use Spiral\DatabaseSeeder\Attribute\Seeder;
use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;
use Tests\Fixture\Entity\Comment;
use Tests\Fixture\Entity\Post;
use Tests\Fixture\Entity\User;
use Tests\Fixture\Factory\PostFactory;
use Tests\Fixture\Factory\UserFactory;

#[Seeder]
class CommentSeeder extends AbstractSeeder
{
    public function run(): \Generator
    {
        /** @var Post $post */
        $post = PostFactory::new()->createOne();
        /** @var User $user */
        $user= UserFactory::new()->createOne();

        $comment = new Comment();
        $comment->post = $post;
        $comment->postedAt = new \DateTimeImmutable();
        $comment->text = 'foo';
        $comment->author = $user;

        yield $comment;
    }
}
