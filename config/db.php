<?php

/**
 * Connection configuration
 */

return [

    /*
     * Connection driver
     */

    'driver' => get_env('db.driver', 'mysql'),

    /*
     * Connection host
     */

    'host' => get_env('db.host', '127.0.0.1'),

    /*
     * Connection name
     */

    'database' => get_env('db.database', ''),

    /*
     * Connection username
     */

    'username' => get_env('db.username', 'root'),

    /*
     * Connection password
     */

    'password' => get_env('db.password', ''),

    /*
     * Connection port
     */

    'port' => get_env('db.port', 3306),

    /*
     * Connection charset
     */

    'charset' => get_env('db.charset', 'utf8mb4'),

    /*
     * Connection collation
     */

    'collation' => get_env('db.collation', 'utf8mb4_unicode_ci'),

    /*
     * Connection prefix
     */

    'prefix' => get_env('db.prefix', ''),

    /*
     * Connection strict mode
     */

    'strict' => get_env('db.strict', false),

    /*
     * Connection options
     */

    'options' => get_env('db.options', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_PERSISTENT => false,
        // PDO::MYSQL_ATTR_SSL_CA => '/path/to/ca.pem',
        // PDO::MYSQL_ATTR_SSL_CERT => '/path/to/client-cert.pem',
        // PDO::MYSQL_ATTR_SSL_KEY => '/path/to/client-key.pem',
    ]),

    /*
     * Connection timezone
     */

    'timezone' => get_env('db.timezone', 'UTC'),

    /*
     * Connection locale
     */

    'locale' => get_env('db.locale', 'en'),

    /*
     * Connection fallback locale
     */

    'fallback_locale' => get_env('db.fallback_locale', 'en'),

    /*
     * Connection fallback timezone
     */

    'fallback_timezone' => get_env('db.fallback_timezone', 'UTC'),

    /*
     * Max retries for connection
     */

    'max_retries' => get_env('db.max_retries', 3),
];
