<?php

declare(strict_types=1);

namespace Tests\Functional;

use Cycle\Database\DatabaseManager;
use Spiral\Boot\Bootloader\ConfigurationBootloader;
use Spiral\Cycle\Bootloader as CycleOrmBridge;
use Spiral\DatabaseSeeder\Bootloader\DatabaseSeederBootloader;
use Spiral\DatabaseSeeder\Database\DatabaseState;

abstract class TestCase extends \Spiral\DatabaseSeeder\TestCase
{
    public const ENV = [
        'DEFAULT_DB' => 'sqlite'
    ];

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanUpRuntimeDirectory();

        $manager = $this->getContainer()->get(DatabaseManager::class);
        foreach ($manager->database('mysql')->getTables() as $table) {
            $schema = $table->getSchema();
            $schema->declareDropped();
            $schema->save();
        }

        DatabaseState::$migrated = false;
    }

    public function rootDirectory(): string
    {
        return \dirname(__DIR__, 2);
    }

    public function defineDirectories(string $root): array
    {
        return [
            'root' => $root,
            'app' => $root . '/app',
            'runtime' => $root . '/app/runtime',
            'cache' => $root . '/app/runtime/cache',
            'config' => $root . '/app/config',
        ];
    }

    public function defineBootloaders(): array
    {
        return [
            ConfigurationBootloader::class,
            CycleOrmBridge\DatabaseBootloader::class,
            CycleOrmBridge\MigrationsBootloader::class,
            CycleOrmBridge\SchemaBootloader::class,
            CycleOrmBridge\CycleOrmBootloader::class,
            CycleOrmBridge\AnnotatedBootloader::class,
            CycleOrmBridge\CommandBootloader::class,
            DatabaseSeederBootloader::class,
        ];
    }
}
