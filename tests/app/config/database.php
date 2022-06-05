<?php

declare(strict_types=1);

use Cycle\Database\Config;

return [
    'default' => 'default',
    'databases' => [
        'default' => [
            'driver' => 'testing',
        ],
    ],
    'drivers' => [
        'testing' => new Config\SQLiteDriverConfig(
            connection: new Config\SQLite\MemoryConnectionConfig(),
            queryCache: true
        ),
    ],
];
