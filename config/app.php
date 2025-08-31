<?php

/**
 * Default application configuration
 */

return [

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
     * Application timezone
     */

    'timezone' => get_env('app.timezone', 'Europe/Sofia'),

    /*
     * Application locale
     */

    'locale' => get_env('app.locale', 'en'),

    /*
     * Application fallback locale
     */

    'fallback_locale' => get_env('app.fallback_locale', 'en'),

    /*
     * Application fallback timezone
     */

    'fallback_timezone' => get_env('app.fallback_timezone', 'UTC'),

    /*
     * Application key
     */

    'key' => get_env('app.key', ''),
];
