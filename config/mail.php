<?php

/**
 * Mail configuration
 */

return [

    'mail' => [
        /**
         * Mail driver
         */
        'driver' => 'smtp',
        /**
         * Mail host
         */
        'host' => 'smtp.example.com',
        /**
         * Mail port
         */
        'port' => 587,
        /**
         * Mail username
         */
        'username' => '',
        /**
         * Mail password
         */
        'password' => '',
        /**
         * Mail encryption
         */
        'encryption' => 'tls',
        /**
         * Mail from
         */
        'from' => [
            'address' => '',
            'name' => '',
        ],
    ],
];
