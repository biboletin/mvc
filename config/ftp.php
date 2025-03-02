<?php

/**
 * FTP configuration
 */

return [

    /*
     * FTP connection settings
     */

    'ftp' => [

        /*
         * Host
         */

        'host' => $_ENV['FTP_HOST'] ?? 'ftp.example.com',

        /*
         * Username
         */

        'username' => $_ENV['FTP_USER'] ?? '',

        /*
         * Password
         */

        'password' => $_ENV['FTP_PASSWORD'] ?? 'password',

        /*
         * Port
         */

        'port' => 21,

        /*
         * Timeout
         */

        'timeout' => 90,

        /*
         * Root directory
         */

        'root' => '/public_html',

        /*
         * Passive mode
         */

        'passive' => true,

        /*
         * SSL
         */

        'ssl' => false,

        /*
         * Public permissions
         */

        'permPublic' => 0755,

        /*
         * Private permissions
         */

        'permPrivate' => 0755,

        /*
         * Ignore passive address
         */

        'ignorePassiveAddress' => false,

        /*
         * Ignore SSL
         */

        'ignoreSSL' => false,

        /*
         * Ignore system type
         */

        'ignoreSystemType' => false,

        /*
         * Ignore timeout
         */

        'ignoreTimeout' => false,

        /*
         * Ignore passive mode
         */

        'ignorePassive' => false,

        /*
         * Ignore transfer mode
         */

        'ignoreMode' => false,

        /*
         * Ignore root
         */

        'ignoreRoot' => false,

        /*
         * Ignore public permissions
         */

        'ignorePermPublic' => false,

        /*
         * Ignore private permissions
         */

        'ignorePermPrivate' => false,

        /*
         * Ignore connection
         */

        'ignoreConnection' => false,

        /*
         * Ignore login
         */

        'ignoreLogin' => false,

        /*
         * Ignore upload
         */

        'ignoreUpload' => false,

        /*
         * Ignore download
         */

        'ignoreDownload' => false,

        /*
         * Ignore delete
         */

        'ignoreDelete' => false,

        /*
         * Ignore rename
         */

        'ignoreRename' => false,

        /*
         * Ignore move
         */

        'ignoreMove' => false,

        /*
         * Ignore chmod
         */

        'ignoreChmod' => false,

        /*
         * Ignore list
         */

        'ignoreList' => false,

        /*
         * Ignore raw command
         */

        'ignoreRaw' => false,

        /*
         * Ignore close
         */

        'ignoreClose' => false,
    ],
];
