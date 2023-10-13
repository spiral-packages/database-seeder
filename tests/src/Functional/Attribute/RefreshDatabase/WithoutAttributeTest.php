<?php

declare(strict_types=1);

namespace Tests\Functional\Attribute\RefreshDatabase;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use Spiral\DatabaseSeeder\Database\Strategy\RefreshStrategy;
use Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase;
use Tests\Functional\TestCase;

final class WithoutAttributeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * All required asserts are in the expectation of the RefreshStrategy mock.
     */
    #[DoesNotPerformAssertions]
    public function testWithoutAttribute():void
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
            ->expects($this->never())
            ->method('setDatabase')
            ->with(null);

        $refresh
            ->expects($this->never())
            ->method('setExcept')
            ->with(['migrations']);

        $refresh
            ->expects($this->never())
            ->method('refresh');

        return $refresh;
    }
}
