<?php
// return [
//     'driver'    => getenv('DB_CONNECTION') ?? 'mysql',
//     'host'      => getenv('DB_HOST') ?? 'localhost',
//     'database'  => getenv('DB_DATABASE'),
//     'username'  => getenv('DB_USERNAME'),
//     'password'  => getenv('DB_PASSWORD'),
//     'charset'   => 'utf8',
//     'collation' => 'utf8_unicode_ci',
//     'prefix'    => '',
// ];
return [
        'default' => 'mysql',

        'connections' => [
            'default' => [
                'driver'    => 'mysql',
                'host'      => '127.0.0.1',
                'database'  => 'campaign_chaser',
                'username'  => 'root',
                'password'  => '',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ],

            'mysql2' => [
                'driver'    => 'mysql',
                'host'      => '127.0.0.1',
                'database'  => 'vas_core',
                'username'  => 'root',
                'password'  => '',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ],
        ],
    ];