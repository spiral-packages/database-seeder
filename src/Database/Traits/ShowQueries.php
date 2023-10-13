<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Cycle\Database\DatabaseInterface;
use Cycle\Database\Driver\DriverInterface;
use Psr\Log\LoggerInterface;
use Spiral\DatabaseSeeder\Attribute\ShowQueries as Attribute;
use Spiral\DatabaseSeeder\Database\Logger\StdoutLogger;

trait ShowQueries
{
    private ?LoggerInterface $originalLogger = null;

    public function showDatabaseQueries(): void
    {
        $driver = $this->getDriver();

        if ($this->originalLogger === null) {
            $this->originalLogger = (new \ReflectionProperty($driver, 'logger'))->getValue($driver);
        }

        $driver->setLogger(new StdoutLogger());
    }

    protected function setUpShowQueries(): void
    {
        if ($this->getTestAttributes(Attribute::class) !== []) {
            $this->showDatabaseQueries();
        }
    }

    protected function tearDownShowQueries(): void
    {
        $this->restoreLogger();
    }

    private function restoreLogger(): void
    {
        if ($this->originalLogger === null) {
            return;
        }

        $this->getDriver()->setLogger($this->originalLogger);

        $this->originalLogger = null;
    }

    private function getDriver(): DriverInterface
    {
        /**
         * @var DatabaseInterface $database
         */
        $database = $this->getContainer()->get(DatabaseInterface::class);

        return $database->getDriver();
    }
}
