<?php

/**
 * Database configuration
 */

return [

    'mysql' => [

        /*
         * Database driver
         */

        'driver' => 'mysql',

        /*
         * Database host
         */

        'host' => $_ENV['DB_HOST'] ?? 'localhost',

        /*
         * Database name
         */

        'database' => $_ENV['DB_DATABASE'] ?? 'example',

        /*
         * Database username
         */

        'username' => $_ENV['DB_USERNAME'] ?? 'root',

        /*
         * Database password
         */

        'password' => $_ENV['DB_PASSWORD'] ?? '',

        /*
         * Database port
         */

        'port' => $_ENV['DB_PORT'] ?? 3306,

        /*
         * Database charset
         */

        'charset' => 'utf8mb4',

        /*
         * Database collation
         */

        'collation' => 'utf8mb4_unicode_ci',

        /*
         * Database prefix
         */

        'prefix' => '',

        /*
         * Database strict mode
         */

        'strict' => true,

        /*
         * Database engine
         */

        'engine' => null,

        /*
         * Database options
         */

        'options' => [],

        /*
         * Database timezone
         */

        'timezone' => 'UTC',

        /*
         * Database locale
         */

        'locale' => 'en',

        /*
         * Database fallback locale
         */

        'fallback_locale' => 'en',

        /*
         * Database fallback timezone
         */

        'fallback_timezone' => 'UTC',
    ],
];
