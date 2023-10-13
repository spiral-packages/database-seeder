<?php

declare(strict_types=1);

namespace Tests\Functional\Console\Command;

use Tests\Functional\TestCase;

final class SeederCommandTest extends TestCase
{
    public function testScaffold(): void
    {
        $this->assertScaffolderCommandContains(
            command: 'create:seeder',
            args: [
                'name' => 'TestSeeder',
            ],
            expectedStrings: [
                'declare(strict_types=1);',
                'namespace Database\\Seeder;',
                'use Spiral\\DatabaseSeeder\\Seeder\\AbstractSeeder;',
                'class TestSeeder extends AbstractSeeder',
                'public function run(): \Generator'
            ],
            expectedFilename: dirname(__DIR__, 4) . '/app/database/Seeder/TestSeeder.php',
            expectedOutputStrings: ['Seeder TestSeeder has been successfully created.']
        );
    }

    public function testScaffoldWithComment(): void
    {
        $this->assertScaffolderCommandContains(
            command: 'create:seeder',
            args: [
                'name' => 'TestSeeder',
                '--comment' => 'Custom comment',
            ],
            expectedStrings: [
                'Custom comment',
                'declare(strict_types=1);',
                'namespace Database\\Seeder;',
                'use Spiral\\DatabaseSeeder\\Seeder\\AbstractSeeder;',
                'class TestSeeder extends AbstractSeeder',
                'public function run(): \Generator'
            ],
            expectedFilename: dirname(__DIR__, 4) . '/app/database/Seeder/TestSeeder.php',
            expectedOutputStrings: ['Seeder TestSeeder has been successfully created.']
        );
    }
}
