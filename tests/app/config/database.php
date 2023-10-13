<?php

declare(strict_types=1);

use Cycle\Database\Config;

return [
    'default' => 'mysql',
    'databases' => [
        'mysql' => [
            'driver' => 'mysql',
        ],
    ],
    'drivers' => [
        'mysql' => new Config\MySQLDriverConfig(
            connection: new Config\MySQL\TcpConnectionConfig(
                database: 'spiral',
                host: '127.0.0.1',
                port: 13306,
                user: 'root',
                password: 'root',
            ),
            queryCache: true
        ),
    ],
];
