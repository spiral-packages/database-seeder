<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\Common\Factory;

use Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase;
use Tests\Database\Factory\UserFactory;
use Tests\Functional\TestCase;

abstract class AbstractFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): void
    {
        $users = UserFactory::new()->times(5)->create();

        $this->assertTableCount('users', 5);
        $this->assertTableHas('users', ['id' => $users[0]->id]);
        $this->assertTableHas('users', ['id' => $users[1]->id]);
        $this->assertTableHas('users', ['id' => $users[2]->id]);
        $this->assertTableHas('users', ['id' => $users[3]->id]);
        $this->assertTableHas('users', ['id' => $users[4]->id]);
    }

    public function testCreateOne(): void
    {
        $user = UserFactory::new()->createOne();

        $this->assertTableCount('users', 1);
        $this->assertTableHas('users', ['id' => $user->id]);
    }
}
