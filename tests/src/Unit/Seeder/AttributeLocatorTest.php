<?php

declare(strict_types=1);

namespace Tests\Unit\Seeder;

use PHPUnit\Framework\TestCase;
use Spiral\Attributes\AttributeReader;
use Spiral\Core\FactoryInterface;
use Spiral\DatabaseSeeder\Seeder\AttributeLocator;
use Spiral\DatabaseSeeder\Seeder\SeederInterface;
use Spiral\Tokenizer\ClassesInterface;
use Tests\Fixture\Seeder\CommentSeeder;
use Tests\Fixture\Seeder\PostSeeder;
use Tests\Fixture\Seeder\UserSeeder;

final class AttributeLocatorTest extends TestCase
{
    public function testFind(): void
    {
        $classes = $this->createMock(ClassesInterface::class);
        $classes->method('getClasses')->willReturn([
            new \ReflectionClass(CommentSeeder::class),
            new \ReflectionClass(PostSeeder::class),
            new \ReflectionClass(UserSeeder::class),
        ]);

        $locator = new AttributeLocator($classes, new AttributeReader(),
            new class() implements FactoryInterface {
            public function make(string $alias, array $parameters = [])
            {
                return new $alias($parameters['priority']);
            }
        });

        $seeders = $locator->find();

        // Only with attributes founded
        $this->assertCount(2, $seeders);

        $comment = \array_values(
            \array_filter($seeders, static fn(SeederInterface $seeder) => $seeder instanceof CommentSeeder)
        )[0];
        $post = \array_values(
            \array_filter($seeders, static fn(SeederInterface $seeder) => $seeder instanceof PostSeeder)
        )[0];

        $this->assertInstanceOf(CommentSeeder::class, $comment);
        $this->assertSame(1, $comment->getPriority());
        $this->assertInstanceOf(PostSeeder::class, $post);
        $this->assertSame(3, $post->getPriority());
    }
}
