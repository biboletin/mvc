<?php

/**
 * Default application configuration
 */

return [

    /*
     * Application settings
     */

    'app' => [

        /*
         * Application name
         */

        'name' => $_ENV['APP_NAME'] ?? 'Example Application',

        /*
         * Application version
         */

        'version' => '1.0.0',

        /*
         * Application environment
         */

        'env' => $_ENV['APP_ENV'] ?? 'development',

        /*
         * Application debug mode
         */

        'debug' => $_ENV['APP_DEBUG'] ?? false,

        /*
         * Application debug level
         */

        'debug_level' => $_ENV['APP_DEBUG_LEVEL'] ?? 'debug',

        /*
         * Application timezone
         */

        'timezone' => 'UTC',

        /*
         * Application locale
         */

        'locale' => 'en',

        /*
         * Application fallback locale
         */

        'fallback_locale' => 'en',

        /*
         * Application fallback timezone
         */

        'fallback_timezone' => 'UTC',

        /*
         * Application URL
         */

        'url' => $_ENV['APP_URL'] ?? 'http://localhost',

        /*
         * Application key
         */

        'key' => '',

        /*
         * Application cipher
         */

        'cipher' => 'AES-256-CBC',

        /*
         * Application hash
         */

        'hash' => 'sha256',

        /*
         * Application hash key
         */

        'hash_key' => '',

        /*
         * Application hash options
         */

        'hash_options' => [
            'cost' => 12,
        ],
    ],
];
