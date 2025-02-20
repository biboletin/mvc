<?php

/**
 * Default cookie configuration
 */

return [

    'cookie' => [
        /**
         * Cookie name
         */
        'name' => 'ExampleCookie',
        /**
         * Cookie expiration time
         */
        'expires' => 3600,
        /**
         * Cookie path
         */
        'path' => '/',
        /**
         * Cookie domain
         */
        'domain' => null,
        /**
         * Cookie secure flag
         */
        'secure' => false,
        /**
         * Cookie HTTP only flag
         */
        'httponly' => true,
        /**
         * Cookie same site attribute
         */
        'samesite' => 'Lax',

        /**
         * Cookie encryption
         */
        'encryption' => [
            'enabled' => false,
            'key' => 'your-encryption-key',
            'cipher' => 'AES-256-CBC',
        ],

        /**
         * Cookie hashids
         */
        'hashids' => [
            'enabled' => false,
            'salt' => 'your-salt',
            'length' => 10,
        ],

        /**
         * Cookie password
         */
        'password' => [
            'algorithm' => PASSWORD_DEFAULT,
            'options' => [
                'cost' => 12,
            ],
        ],

        /**
         * Cookie OTP
         */
        'otp' => [
            'length' => 6,
            'ttl' => 300,
        ],

        /**
         * Cookie auth
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

        /**
         * Cookie recaptcha
         */
        'recaptcha' => [
            'enabled' => false,
            'site_key' => '',
        ],

        /**
         * Cookie csrf
         */
        'csrf' => [
            'key' => 'csrf-token',
            'expires' => 3600,
        ],

        /**
         * Cookie cors
         */
        'cors' => [
            'enabled' => false,
            'origin' => '*',
            'methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
            'headers' => ['Content-Type', 'Authorization'],
            'exposed' => [],
            'max_age' => 0,
        ],

        /**
         * Cookie jwt
         */
        'jwt' => [
            'enabled' => false,
        ],
    ],
];
