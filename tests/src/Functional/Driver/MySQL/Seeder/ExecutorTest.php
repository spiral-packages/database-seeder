<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\MySQL\Seeder;

use Tests\Functional\Driver\Common\Seeder\ExecutorTest as CommonExecutorTest;

/**
 * @group driver
 * @group driver-mysql
 *
 * Tests in conventional database (MySQL)
 */
final class ExecutorTest extends CommonExecutorTest
{
    public const ENV = [
        'DEFAULT_DB' => 'mysql'
    ];
}
