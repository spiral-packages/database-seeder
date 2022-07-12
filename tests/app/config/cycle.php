<?php

return [

    'schema' => [
        'collections' => [
            'default' => 'array',
            'factories' => [
                'array' => new \Cycle\ORM\Collection\ArrayCollectionFactory(),
                'doctrine' => new \Cycle\ORM\Collection\DoctrineCollectionFactory(),
            ],
        ],
    ]
];
