<?php 

return [
    'name' => 'Advanced Dashboard',
    'url' => 'http://dashboard.test/',
    'debug' => true,
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'my_app',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
    'session' => [
        'driver' => 'file',
        'lifetime' => 120,
        'expire_on_close' => false,
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'http_only' => true,
        'same_site' => null,
    ],
    'cache' => [
        'driver' => 'file',
        'lifetime' => 60,
        'path' => __DIR__ . '/../cache',
    ],
    'mail' => [
        'driver' => 'smtp',
        'host' => 'smtp.example.com',
        'port' => 587,
        'username' => 'your-email',
        'password' => 'your-password',
        'encryption' => 'tls',
        'from' => [
            'address' => 'your-email@example.com',
            'name' => 'Your Name',
        ], ],
    'log' => [
        'driver' => 'single',
        'path' => __DIR__ . '/../logs/app.log',
    ]
];