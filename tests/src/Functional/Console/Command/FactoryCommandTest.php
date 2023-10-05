<?php

declare(strict_types=1);

namespace Tests\Functional\Console\Command;

use Tests\Functional\TestCase;

final class FactoryCommandTest extends TestCase
{
    public function testScaffold(): void
    {
        $this->assertScaffolderCommandContains(
            command: 'create:factory',
            args: [
                'name' => 'TestFactory',
            ],
            expectedStrings: [
                'declare(strict_types=1);',
                'namespace Database\\Factory;',
                'use Spiral\\DatabaseSeeder\\Factory\\AbstractFactory;',
                'class TestFactory extends AbstractFactory',
                'public function entity(): string',
                'public function definition(): array',
            ],
            expectedFilename: dirname(__DIR__, 4) . '/app/database/Factory/TestFactory.php',
            expectedOutputStrings: ['Factory TestFactory has been successfully created.']
        );
    }

    public function testScaffoldWithComment(): void
    {
        $this->assertScaffolderCommandContains(
            command: 'create:factory',
            args: [
                'name' => 'TestFactory',
                '--comment' => 'Custom comment',
            ],
            expectedStrings: [
                'Custom comment',
                'declare(strict_types=1);',
                'namespace Database\\Factory;',
                'use Spiral\\DatabaseSeeder\\Factory\\AbstractFactory;',
                'class TestFactory extends AbstractFactory',
                'public function entity(): string',
                'public function definition(): array',
            ],
            expectedFilename: dirname(__DIR__, 4) . '/app/database/Factory/TestFactory.php',
            expectedOutputStrings: ['Factory TestFactory has been successfully created.']
        );
    }
}
