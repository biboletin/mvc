<?php

if (!defined('VIEW_PATH')) {
    /*
     * Views directory path
     */

    define('VIEW_PATH', realpath(__DIR__ . '/../../resources/views/') . DIRECTORY_SEPARATOR);
}

if (!defined('CACHE_PATH')) {
    /*
     * Cache directory path
     */

    define('CACHE_PATH', realpath(__DIR__ . '/../../storage/cache/') . DIRECTORY_SEPARATOR);
}

if (!defined('LOG_PATH')) {
    /*
     * Logs directory path
     */

    define('LOG_PATH', realpath(__DIR__ . '/../../storage/logs/') . DIRECTORY_SEPARATOR);
}

if (!defined('CONFIG_PATH')) {
    /*
     * Config files directory path
     */

    define('CONFIG_PATH', realpath(__DIR__ . '/../../config/') . DIRECTORY_SEPARATOR);
}

if (!defined('ROUTES_PATH')) {
    /*
     * Routes directory path
     */

    define('ROUTES_PATH', realpath(__DIR__ . '/../../routes/') . DIRECTORY_SEPARATOR);
}

if (!defined('APP_PATH')) {
    /*
     * Application path
     */

    define('APP_PATH', realpath(__DIR__ . '/../../app/') . DIRECTORY_SEPARATOR);
}

if (!defined('PUBLIC_PATH')) {
    /*
     * Public path
     */

    define('PUBLIC_PATH', realpath(__DIR__ . '/../../public/') . DIRECTORY_SEPARATOR);
}

if (!defined('STORAGE_PATH')) {
    /*
     * Storage path
     */

    define('STORAGE_PATH', realpath(__DIR__ . '/../../storage/') . DIRECTORY_SEPARATOR);
}

if (!defined('SRC_PATH')) {
    /*
     * Src directory path
     */

    define('SRC_PATH', realpath(__DIR__ . '/../../src/') . DIRECTORY_SEPARATOR);
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

    define('VENDOR_PATH', realpath(__DIR__ . '/../../vendor/') . DIRECTORY_SEPARATOR);
}

if (!defined('BASE_PATH')) {
    /*
     * Base directory path
     */

    define('BASE_PATH', realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR);
}

if (!defined('CONTROLLER_PATH')) {
    /*
     * Controllers directory path
     */

    define('CONTROLLER_PATH', realpath(__DIR__ . '/../../app/Controllers/') . DIRECTORY_SEPARATOR);
}

if (!defined('MIDDLEWARE_PATH')) {
    /*
     * Middlewares directory path
     */

    define('MIDDLEWARE_PATH', realpath(__DIR__ . '/../../app/Middleware/') . DIRECTORY_SEPARATOR);
}

if (!defined('MODEL_PATH')) {
    /*
     * Models directory path
     */

    define('MODEL_PATH', realpath(__DIR__ . '/../../app/Models/') . DIRECTORY_SEPARATOR);
}

