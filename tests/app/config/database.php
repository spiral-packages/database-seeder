<?php

declare(strict_types=1);

use Cycle\Database\Config;

return [
    'default' => env('DEFAULT_DB'),
    'databases' => [
        'sqlite' => [
            'driver' => 'sqlite',
        ],
        'mysql' => [
            'driver' => 'mysql',
        ],
    ],
    'drivers' => [
        'sqlite' => new Config\SQLiteDriverConfig(
            connection: new Config\SQLite\MemoryConnectionConfig(),
            queryCache: true
        ),
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
