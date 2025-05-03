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

        'name' => config('app_name', 'Example Application'),

        /*
         * Application URL
         */

        'url' => config('app_url', 'http://localhost'),

        /*
         * Application version
         */

        'version' => '1.0.0',

        /*
         * Application environment
         */

        'env' => config('app_env', 'development'),

        /*
         * Application debug mode
         */

        'debug' => config('app_debug', false),

        /*
         * Application debug level
         */

        'debug_level' => config('app_debug_level', 'debug'),

        /*
         * Application timezone
         */

        'timezone' => date_default_timezone_set(config('app_timezone', 'UTC')),

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
