<?php

/**
 * Session configuration
 */

return [

    'session' => [

        /*
         * Session name
         */

        'name' => $_ENV['SESSION_NAME'] ?? 'ExampleSession',

        /*
         * Session lifetime
         */

        'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 3600,

        /*
         * Session path
         */

        'path' => $_ENV['SESSION_PATH'] ?? '/',

        /*
         * Session domain
         */

        'domain' => $_ENV['session_domain'] ?? null,

        /*
         * Session secure flag
         */

        'secure' => $_ENV['SESSION_SECURE'] ?? false,

        /*
         * Session HTTP only flag
         */

        'httponly' => $_ENV['SESSION_HTTP'] ?? true,

        /*
         * Session same site attribute
         */

        'samesite' => $_ENV['SESSION_SAMESITE'] ?? 'Lax',

        /*
         * Session encryption
         */

        'encryption' => [
            'enabled' => false,
            'key' => 'your-encryption-key',
            'cipher' => 'AES-256-CBC',
        ],

        /*
         * Session hashids
         */

        'hashids' => [
            'enabled' => false,
            'salt' => 'your-salt',
            'length' => 10,
        ],

        /*
         * Session password
         */

        'password' => [
            'algorithm' => PASSWORD_DEFAULT,
            'options' => [
                'cost' => 12,
            ],
        ],

        /*
         * Session database
         */

        'database' => [
            'enabled' => false,
            'connection' => 'default',
            'table' => 'sessions',
        ],

        /*
         * Session cache
         */

        'cache' => [
            'enabled' => false,
            'store' => 'default',
            'key' => 'sessions',
            'ttl' => 3600,
        ],

        /*
         * Session CSRF
         */

        'csrf' => [
            'key' => 'csrf-token',
            'expires' => 3600,
        ],

        /*
         * Session CORS
         */

        'cors' => [
            'enabled' => false,
            'origin' => '*',
            'methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
            'headers' => ['Content-Type', 'Authorization'],
            'exposed' => [],
            'max_age' => 0,
        ],

        /*
         * Session JWT
         */

        'jwt' => [
            'enabled' => false,
        ],

        /*
         * Session recaptcha
         */

        'recaptcha' => [
            'enabled' => false,
            'site_key' => '',
        ],

        /*
         * Session auth
         */

        'auth' => [
            'enabled' => false,
            'model' => 'App\Models\User',
            'field' => 'email',
            'password' => 'password',
            'remember' => 'remember_token',
            'otp' => 'otp',
            'jwt' => 'jwt',
            'hash' => 'hash',
            'hash_algo' => 'sha256',
            'hash_options' => [
                'cost' => 12,
            ],
        ],

        /*
         * Session cookies
         */

        'cookies' => [],

        /*
         * Session middleware
         */

        'middleware' => [
            'session' => [
                'class' => 'Biboletin\Middleware\SessionMiddleware',
                'arguments' => [
                    'session' => 'session',
                ]
            ]
        ],

        /*
         * Session redirect
         */

        'redirect' => [
            'class' => 'Biboletin\Response\RedirectResponse',
            'arguments' => [
                'url' => 'http://example.com',
                'statusCode' => 200,
                'headers' => []
            ]
        ],
    ],
];
