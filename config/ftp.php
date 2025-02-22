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
        'host' => 'ftp.example.com',
        /*
         * Username
         */
        'username' => 'username',
        /*
         * Password
         */
        'password' => 'password',
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
         * Transfer mode
         */
        'mode' => FTP_BINARY,
        /*
         * System type
         */
        'systemType' => FTP_AUTO_DETECT,
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
