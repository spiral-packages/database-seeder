<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\MySQL\Factory;

use Tests\Functional\Driver\Common\Factory\AbstractFactoryTest as CommonAbstractFactoryTest;

/**
 * @group driver
 * @group driver-mysql
 *
 * Tests in conventional database (MySQL)
 */
final class AbstractFactoryTest extends CommonAbstractFactoryTest
{
    public const ENV = [
        'DEFAULT_DB' => 'mysql'
    ];
}
