<?php

declare(strict_types=1);

namespace Tests\Functional\Attribute\RefreshDatabase;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use Spiral\DatabaseSeeder\Attribute\RefreshDatabase as Attribute;
use Spiral\DatabaseSeeder\Database\Strategy\RefreshStrategy;
use Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase;
use Tests\Functional\TestCase;

final class WithAttributeAndParametersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * All required asserts are in the expectation of the RefreshStrategy mock.
     */
    #[Attribute(database: 'secondary', except: ['users'])]
    #[DoesNotPerformAssertions]
    public function testWithAttributeAndParameters():void
    {
    }

    protected function getRefreshStrategy(): RefreshStrategy
    {
        $refresh = $this->createMock(RefreshStrategy::class);
        $refresh
            ->expects($this->once())
            ->method('isRefreshAttributeEnabled')
            ->willReturn(true);

        $refresh
            ->expects($this->once())
            ->method('setDatabase')
            ->with('secondary');

        $refresh
            ->expects($this->once())
            ->method('setExcept')
            ->with(['users', 'migrations']);

        $refresh
            ->expects($this->once())
            ->method('refresh');

        return $refresh;
    }
}
