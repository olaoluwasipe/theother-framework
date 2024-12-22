<?php
return [
    'cache.default' => 'database',
    'cache.stores.file' => [
        'driver' => 'file',
        'path' => __DIR__ . '/../storage/cache',
    ],
    'cache.stores.database' => [
        'driver' => 'database',
        'table' => 'cache',
        'prefix' => 'cache_',
        'connection' => null, // Use default connection or specify one
        'lock_connection' => null, // Optional for cache locks
    ],
    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => __DIR__ . '/../storage/cache',
        ],
        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'prefix' => 'cache_',
            'connection' => null, // Use default connection or specify one
            'lock_connection' => null, // Optional for cache locks
        ]
    ],
    'prefix' => 'custom_framework',
];
