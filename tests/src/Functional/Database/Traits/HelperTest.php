<?php

declare(strict_types=1);

namespace Tests\Functional\Database\Traits;

use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseProviderInterface;
use Cycle\Database\Driver\DriverInterface;
use Cycle\ORM\Heap\HeapInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Spiral\DatabaseSeeder\Database\Cleaner;
use Spiral\DatabaseSeeder\Database\Traits\Helper;
use Tests\Functional\TestCase;

final class HelperTest extends TestCase
{
    use Helper;

    public function testGetDatabaseCleaner(): void
    {
        $cleaner = $this->getDatabaseCleaner();

        $this->assertInstanceOf(Cleaner::class, $cleaner);
        $this->assertSame($cleaner, $this->getDatabaseCleaner());
    }

    public function testGetCurrentDatabase(): void
    {
        $this->assertInstanceOf(DatabaseInterface::class, $this->getCurrentDatabase());
    }

    public function testGetCurrentDatabaseDriver(): void
    {
        $this->assertInstanceOf(DriverInterface::class, $this->getCurrentDatabaseDriver());
    }

    public function testGetCurrentDatabaseProvider(): void
    {
        $this->assertInstanceOf(DatabaseProviderInterface::class, $this->getCurrentDatabaseProvider());
    }

    public function testGetOrm(): void
    {
        $this->assertInstanceOf(ORMInterface::class, $this->getOrm());
    }

    public function testDetachEntityFromIdentityMap(): void
    {
        $heap = $this->createMock(HeapInterface::class);
        $heap
            ->expects($this->once())
            ->method('detach')
            ->with($entity = new \stdClass());

        $orm = $this->createMock(ORMInterface::class);
        $orm
            ->expects($this->once())
            ->method('getHeap')
            ->willReturn($heap);
        $this->getContainer()->bindSingleton(ORMInterface::class, $orm, true);

        $this->detachEntityFromIdentityMap($entity);
    }

    public function testCleanIdentityMap(): void
    {
        $heap = $this->createMock(HeapInterface::class);
        $heap
            ->expects($this->once())
            ->method('clean');

        $orm = $this->createMock(ORMInterface::class);
        $orm
            ->expects($this->once())
            ->method('getHeap')
            ->willReturn($heap);
        $this->getContainer()->bindSingleton(ORMInterface::class, $orm, true);

        $this->cleanIdentityMap();
    }

    public function testGetRepositoryFor(): void
    {
        $repository = $this->createMock(RepositoryInterface::class);

        $orm = $this->createMock(ORMInterface::class);
        $orm
            ->expects($this->once())
            ->method('getRepository')
            ->with($entity = new \stdClass())
            ->willReturn($repository);
        $this->getContainer()->bindSingleton(ORMInterface::class, $orm, true);

        $this->assertSame($repository, $this->getRepositoryFor($entity));
    }

    public function testGetRepositoryForByString(): void
    {
        $repository = $this->createMock(RepositoryInterface::class);

        $orm = $this->createMock(ORMInterface::class);
        $orm
            ->expects($this->once())
            ->method('getRepository')
            ->with('someEntity')
            ->willReturn($repository);
        $this->getContainer()->bindSingleton(ORMInterface::class, $orm, true);

        $this->assertSame($repository, $this->getRepositoryFor('someEntity'));
    }
}
