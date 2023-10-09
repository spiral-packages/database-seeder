<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Strategy;

use PHPUnit\Framework\TestCase;
use Spiral\DatabaseSeeder\Database\Cleaner;
use Spiral\DatabaseSeeder\Database\Strategy\RefreshStrategy;

final class RefreshStrategyTest extends TestCase
{
    public function testDefaultUseAttribute(): void
    {
        $strategy = new RefreshStrategy($this->createMock(Cleaner::class));
        $this->assertFalse($strategy->isRefreshAttributeEnabled());

        $strategy = new RefreshStrategy(cleaner: $this->createMock(Cleaner::class), useAttribute: true);
        $this->assertTrue($strategy->isRefreshAttributeEnabled());
    }

    public function testEnableAttribute(): void
    {
        $strategy = new RefreshStrategy($this->createMock(Cleaner::class));

        $this->assertFalse($strategy->isRefreshAttributeEnabled());

        $strategy->enableRefreshAttribute();

        $this->assertTrue($strategy->isRefreshAttributeEnabled());
    }

    public function testDisableAttribute(): void
    {
        $strategy = new RefreshStrategy($this->createMock(Cleaner::class));

        $strategy->enableRefreshAttribute();
        $this->assertTrue($strategy->isRefreshAttributeEnabled());

        $strategy->disableRefreshAttribute();
        $this->assertFalse($strategy->isRefreshAttributeEnabled());
    }

    public function testRefresh(): void
    {
        $cleaner = $this->createMock(Cleaner::class);
        $cleaner->expects($this->once())->method('refreshDb')->with(database: 'testDb', except: ['test']);

        $strategy = new RefreshStrategy($cleaner);
        $strategy->setDatabase('testDb');
        $strategy->setExcept(['test']);

        $strategy->refresh();
    }
}
