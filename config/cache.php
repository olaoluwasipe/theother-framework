<?php
return [
    'cache.default' => 'file',
    'cache.stores.file' => [
        'driver' => 'file',
        'path' => __DIR__ . '/../storage/cache',
    ],
    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => __DIR__ . '/../storage/cache',
        ],
    ],
    'prefix' => 'custom_framework',
];
