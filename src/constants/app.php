<?php

if (!defined('RESOURCES_PATH')) {
    /*
     * Resources path
     */

    define(
        'RESOURCES_PATH',
        realpath(
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources'
        ) . DIRECTORY_SEPARATOR
    );
}

if (!defined('VIEW_PATH')) {
    /*
     * Views directory path
     */

    define('VIEW_PATH', realpath(RESOURCES_PATH . 'views') . DIRECTORY_SEPARATOR);
}

if (!defined('STORAGE_PATH')) {
    /*
     * Storage path
     */

    define(
        'STORAGE_PATH',
        realpath(
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'storage'
        ) . DIRECTORY_SEPARATOR
    );
}

if (!defined('CACHE_PATH')) {
    /*
     * Cache directory path
     */

    define('CACHE_PATH', realpath(STORAGE_PATH . '/cache') . DIRECTORY_SEPARATOR);
}

if (!defined('LOG_PATH')) {
    /*
     * Logs directory path
     */

    define('LOG_PATH', realpath(STORAGE_PATH . '/logs') . DIRECTORY_SEPARATOR);
}

if (!defined('CONFIG_PATH')) {
    /*
     * Config files directory path
     */

    define('CONFIG_PATH', realpath(__DIR__ . '/../../config') . DIRECTORY_SEPARATOR);
}

if (!defined('ROUTES_PATH')) {
    /*
     * Routes directory path
     */

    define('ROUTES_PATH', realpath(__DIR__ . '/../../routes') . DIRECTORY_SEPARATOR);
}

if (!defined('APP_PATH')) {
    /*
     * Application path
     */

    define('APP_PATH', realpath(__DIR__ . '/../../app') . DIRECTORY_SEPARATOR);
}

if (!defined('PUBLIC_PATH')) {
    /*
     * Public path
     */

    define('PUBLIC_PATH', realpath(__DIR__ . '/../../public') . DIRECTORY_SEPARATOR);
}

if (!defined('SRC_PATH')) {
    /*
     * Src directory path
     */

    define('SRC_PATH', realpath(__DIR__ . '/../../src') . DIRECTORY_SEPARATOR);
}

if (!defined('ROOT_PATH')) {
    /*
     * Root directory path
     */

    define('ROOT_PATH', realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR);
}

if (!defined('VENDOR_PATH')) {
    /*
     * Vendor directory path
     */

    define('VENDOR_PATH', realpath(__DIR__ . '/../../vendor') . DIRECTORY_SEPARATOR);
}

if (!defined('CONTROLLER_PATH')) {
    /*
     * Controllers directory path
     */

    define('CONTROLLER_PATH', realpath(__DIR__ . '/../../app/Controllers') . DIRECTORY_SEPARATOR);
}

if (!defined('MIDDLEWARE_PATH')) {
    /*
     * Middlewares directory path
     */

    define('MIDDLEWARE_PATH', realpath(__DIR__ . '/../../app/Middleware') . DIRECTORY_SEPARATOR);
}

if (!defined('MODEL_PATH')) {
    /*
     * Models directory path
     */

    define('MODEL_PATH', realpath(__DIR__ . '/../../app/Models') . DIRECTORY_SEPARATOR);
}

if (!defined('BOOTSTRAP_PATH')) {
    /*
     * Services directory path
     */

    define('BOOTSTRAP_PATH', realpath(__DIR__ . '/../../bootstrap') . DIRECTORY_SEPARATOR);
}

if (!defined('APP_CACHE_PATH')) {
    /*
     * Application cache directory path
     */

    define('APP_CACHE_PATH', CACHE_PATH . 'app' . DIRECTORY_SEPARATOR);
}

if (!defined('CONFIG_CACHE_PATH')) {
    /*
     * Config cache directory path
     */

    define('CONFIG_CACHE_PATH', CACHE_PATH . 'config' . DIRECTORY_SEPARATOR);
}

if (!defined('ROUTES_CACHE_PATH')) {
    /*
     * Routes cache directory path
     */

    define('ROUTES_CACHE_PATH', CACHE_PATH . 'routes' . DIRECTORY_SEPARATOR);
}

if (!defined('COOKIE_JAR_PATH')) {
    /*
     * Cookies path
     */

    define('COOKIES_PATH', STORAGE_PATH . 'cookies' . DIRECTORY_SEPARATOR);
}

if (!defined('COOKIE_JAR_PATH')) {
    /*
     * CookieJar path
     */

    define('COOKIE_JAR_PATH', COOKIES_PATH . 'jar' . DIRECTORY_SEPARATOR);
}
