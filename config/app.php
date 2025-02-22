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
        'name' => 'Example Application',
        /*
         * Application version
         */
        'version' => '1.0.0',
        /*
         * Application environment
         */
        'env' => 'development',
        /*
         * Application debug mode
         */
        'debug' => true,

        'debug_level' => 'debug',
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
        'url' => 'http://localhost',
        /*
         * Application URL with index file
         */
        'index' => 'index.php',
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
