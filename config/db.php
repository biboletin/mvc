<?php

/**
 * Database connection configuration.
 *
 * This configuration supports multiple database drivers using canonical keys:
 * - MySQL / MariaDB  => 'mysql'
 * - PostgreSQL       => 'pgsql'
 * - SQLite           => 'sqlite'
 *
 * Driver aliases like 'mariadb', 'postgres', 'postgresql', 'sqlite3'
 * are resolved using DriverFactory::resolve().
 */

return [

    /*
     * Selected database driver.
     * Can be an alias ('mariadb', 'postgresql') or canonical ('mysql', 'pgsql', 'sqlite').
     * Used by DriverFactory to select the correct driver class and configuration.
     */

    'driver' => get_env('db.driver', 'mysql'),

    /*
     * Canonical MySQL / MariaDB configuration
     */

    'mysql' => [

        /*
         * Database host or IP address
         */

        'host' => get_env('db.host', '127.0.0.1'),

        /*
         * Database name
         */

        'database' => get_env('db.database', ''),

        /*
         * Username for database authentication
         */

        'username' => get_env('db.username', 'root'),

        /*
         * Password for database authentication
         */

        'password' => get_env('db.password', ''),

        /*
         * Database port (default MySQL port)
         */

        'port' => get_env('db.port', 3306),

        /*
         * Character set for connection
         */

        'charset' => get_env('db.charset', 'utf8mb4'),

        /*
         * Collation for connection (MySQL-specific)
         */

        'collation' => get_env('db.collation', 'utf8mb4_unicode_ci'),

        /*
         * Table prefix (useful for multi-tenancy or shared DB)
         */

        'prefix' => get_env('db.prefix', ''),

        /*
         * Strict mode enables more strict SQL behavior (throws errors for warnings)
         */

        'strict' => get_env('db.strict', false),

        /*
         * Timezone for session
         */

        'timezone' => get_env('db.timezone', 'UTC'),

        /*
         * Locale for application-specific formatting
         */

        'locale' => get_env('db.locale', 'en'),

        /*
         * Fallback locale if the primary locale fails
         */

        'fallback_locale' => get_env('db.fallback_locale', 'en'),

        /*
         * Fallback timezone if the primary timezone is invalid
         */

        'fallback_timezone' => get_env('db.fallback_timezone', 'UTC'),

        /*
         * PDO options for connection
         */

        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,    // Return results as objects
            PDO::ATTR_EMULATE_PREPARES => false,               // Use native prepared statements
            PDO::ATTR_STRINGIFY_FETCHES => false,              // Keep numeric types
            PDO::ATTR_PERSISTENT => false,                     // Disable persistent connections
        ],

        /*
         * Number of retries when connecting fails
         */

        'max_retries' => get_env('db.max_retries', 3),
    ],

    /*
     * Canonical PostgreSQL configuration
     */

    'pgsql' => [

        /*
         * Database host or IP address
         */

        'host' => get_env('db.host', '127.0.0.1'),

        /*
         * Database name
         */

        'database' => get_env('db.database', ''),

        /*
         * Username for database authentication
         */

        'username' => get_env('db.username', 'postgres'),

        /*
         * Password for database authentication
         */

        'password' => get_env('db.password', ''),

        /*
         * Database port (default MySQL port)
         * Default PostgreSQL port
         */

        'port' => get_env('db.port', 5432),

        /*
         * Character set for connection
         * PostgreSQL uses UTF-8
         */

        'charset' => get_env('db.charset', 'utf8'),

        /*
         * PostgreSQL schema
         */

        'schema' => get_env('db.schema', 'public'),

        /*
         * PDO options for connection
         */

        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,   // Use native prepares for PostgreSQL
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_PERSISTENT => false,
        ],

        /*
         * Number of retries when connecting fails
         */

        'max_retries' => get_env('db.max_retries', 3),
    ],

    /*
     * Canonical SQLite configuration
     */

    'sqlite' => [

        /*
         * Database file name (stored under DATABASE_PATH + name + ".sqlite")
         */

        'database' => get_env('db.database', 'database'),

        /*
         * PDO options for connection
         */

        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => true,  // SQLite benefit: must emulate
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_PERSISTENT => false,
        ],

        /*
         * Number of retries when connecting fails
         */

        'max_retries' => get_env('db.max_retries', 3),
    ],

    /*
     * Redis configuration placeholder (future support)
     */

    'redis' => [],
];
