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

        'name' => get_env('app.name', 'Example Application'),

        /*
         * Application URL
         */

        'url' => get_env('app.url', 'http://localhost'),

        /*
         * Application version
         */

        'version' => get_env('app.version', '1.0.0'),

        /*
         * Application environment
         */

        'env' => get_env('app.env', 'development'),

        /*
         * Application debug mode
         */

        'debug' => get_env('app.debug', false),

        /*
         * Application debug level
         */

        'debug_level' => get_env('app.debug_level', 'debug'),

        /*
         * Application timezone
         */

        'timezone' => get_env('app.timezone', 'Europe/Sofia'),

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
