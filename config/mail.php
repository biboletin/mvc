<?php

/**
 * Mail configuration
 */

return [

    'mail' => [

        /*
         * Mail driver
         */

        'driver' => 'smtp',

        /*
         * Mail host
         */

        'host' => $_ENV['MAIL_HOST'] ?? 'smtp.example.com',

        /*
         * Mail port
         */

        'port' => $_ENV['MAIL_PORT'] ?? 587,

        /*
         * Mail username
         */

        'username' => $_ENV['MAIL_USER'] ?? '',

        /*
         * Mail password
         */

        'password' => $_ENV['MAIL_PASS'] ?? '',

        /*
         * Mail encryption
         */

        'encryption' => $_ENV['MAIL_TLS'] ?? 'tls',

        /*
         * Mail from
         */

        'from' => [
            'address' => '',
            'name' => '',
        ],
    ],
];
