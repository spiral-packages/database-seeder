<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\Common\Seeder;

use Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase;
use Spiral\DatabaseSeeder\Seeder\Executor;
use Tests\Database\Factory\UserFactory;
use Tests\Database\Factory\WithCompositePkFactory;
use Tests\Database\Seeder\UserSeeder;
use Tests\Database\Seeder\WithCompositePkSeeder;
use Tests\Functional\TestCase;

abstract class ExecutorTest extends TestCase
{
    use RefreshDatabase;

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
        $this->assertTableCount('users', 0);

        $executor = $this->getContainer()->get(Executor::class);
        $executor->execute([new UserSeeder()]);

        $this->assertTableCount('users', 1);
    }

    public function testSeedWithMethodCreateAndCompositePk(): void
    {
        $this->assertTableCount('composite_pk', 0);

        $executor = $this->getContainer()->get(Executor::class);
        $executor->execute([new WithCompositePkSeeder()]);

        $this->assertTableCount('composite_pk', 1);
    }
}
