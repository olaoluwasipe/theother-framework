
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    |
    | Register middleware aliases for easier reference in routes and controllers
    |
    */
    'aliases' => [
        'auth' => \App\Middleware\AuthMiddleware::class,
        // 'guest' => \App\Middleware\GuestMiddleware::class,
        'admin' => \App\Middleware\AdminMiddleware::class,
        // 'api' => \App\Middleware\ApiMiddleware::class,
        // 'throttle' => \App\Middleware\ThrottleRequests::class,
        // 'verified' => \App\Middleware\EnsureEmailIsVerified::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware are run during every request to your application
    |
    */
    'global' => [
        // \App\Middleware\TrimStrings::class,
        // \App\Middleware\SecurityHeaders::class,
        // \App\Middleware\EncryptCookies::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Middleware Groups
    |--------------------------------------------------------------------------
    |
    | Group multiple middleware under a single key for convenience
    |
    */
    'groups' => [
        'web' => [
            'auth',
            \App\Middleware\StartSession::class,
            \App\Middleware\VerifyCsrfToken::class,
        ],
        'api' => [
            'throttle:60,1',
            'api',
        ],
    ],
];
