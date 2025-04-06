<?php

/**
 * Backup configuration
 */

return [

    /*
     * Enable or disable backup
     */

    'backup' => [

        /*
         * Enable or disable backup
         */

        'enabled' => $_ENV['BACKUP_ENABLED'] ?? false,

        /*
         * Backup path
         */

        'path' => $_ENV['BACKUP_PATH'] ?? 'backup',

        /*
         * Backup filename
         */

        'filename' => 'backup',

        /*
         * Backup file extension
         */

        'extension' => 'zip',

        /*
         * Backup file name format
         */

        'format' => 'Y-m-d-H-i-s',

        /*
         * Backup file name
         */

        'name' => null,

        /*
         * Backup file name prefix
         */

        'prefix' => null,

        /*
         * Backup file name suffix
         */

        'suffix' => null,

        /*
         * Backup file name separator
         */

        'separator' => '-',

        /*
         * Backup file name extension
         */

        'ext' => null,

        /*
         * Backup file name timestamp
         */

        'timestamp' => null,

        /*
         * Backup file name date format
         */

        'date' => null,

        /*
         * Backup file name time format
         */

        'time' => null,

        /*
         * Exclude directories
         */

        'exclude' => [
            'backup',
            'cache',
            'logs',
            'tmp',
            'vendor',
        ],

        /*
         * Exclude files
         */

        'exclude_files' => [
            '.gitignore',
            '.htaccess',
            'index.tpl',
            'robots.txt',
            'web.config',
        ],

        /*
         * Exclude extensions
         */

        'exclude_extensions' => [
            'bak',
            'bat',
            'cmd',
            'git',
            'gitignore',
            'htaccess',
            'log',
            'sql',
            'sqlite',
            'sqlite3',
            'swp',
            'tmp',
            'txt',
            'zip',
        ],

        /*
         * Exclude patterns
         */

        'exclude_patterns' => [
            '/^\.git/',
            '/^\.svn/',
            '/^\.vscode/',
            '/^node_modules/',
            '/^vendor/',
        ],
    ],
];
