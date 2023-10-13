<?php

declare(strict_types=1);

namespace Tests\Functional\Seeder;

use Spiral\DatabaseSeeder\Database\Traits\DatabaseMigrations;
use Spiral\DatabaseSeeder\Seeder\Executor;
use Tests\Database\Factory\UserFactory;
use Tests\Database\Factory\WithCompositePkFactory;
use Tests\Database\Seeder\UserSeeder;
use Tests\Database\Seeder\WithCompositePkSeeder;
use Tests\Functional\TestCase;

final class ExecutorTestCase extends TestCase
{
    use DatabaseMigrations;

    public function testIsNotExists(): void
    {
        $executor = $this->getContainer()->get(Executor::class);
        $method = new \ReflectionMethod($executor, 'isNotExists');

        $compositePk = WithCompositePkFactory::new()->makeOne();
        $compositePk->id = 1;
        $compositePk->otherId = 3;

        $simplePk = UserFactory::new()->makeOne();
        $simplePk->id = 1;

        $unInitializedPk = UserFactory::new()->makeOne();

        $this->assertTrue($method->invoke($executor, $compositePk));
        $this->assertTrue($method->invoke($executor, $simplePk));
        $this->assertTrue($method->invoke($executor, $unInitializedPk));
    }

    public function testIsExists(): void
    {
        $executor = $this->getContainer()->get(Executor::class);
        $method = new \ReflectionMethod($executor, 'isNotExists');

        $compositePk = WithCompositePkFactory::new()->createOne();
        $simplePk = UserFactory::new()->createOne();

        $this->assertFalse($method->invoke($executor, $compositePk));
        $this->assertFalse($method->invoke($executor, $simplePk));
    }

    public function testSeed(): void
    {
        $this->assertTable('users')->assertCountRecords(0);

        $executor = $this->getContainer()->get(Executor::class);
        $executor->execute([new UserSeeder()]);

        $this->assertTable('users')->assertCountRecords(1);
    }

    public function testSeedWithMethodCreateAndCompositePk(): void
    {
        $this->assertTable('composite_pk')->assertCountRecords(0);

        $executor = $this->getContainer()->get(Executor::class);
        $executor->execute([new WithCompositePkSeeder()]);

        $this->assertTable('composite_pk')->assertCountRecords(1);
    }
}
