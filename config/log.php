<?php

/**
 * Log configuration file
 *
 * This file contains the configuration settings for logging in the application.
 * It allows you to customize various aspects of logging, such as the log channel,
 * log level, file rotation, and formatting.
 */

return [

    /*
     * The log channel to use for logging.
     * Default is 'stack', which uses multiple channels.
     *
     * Available channels: single, daily, syslog, errorlog, stack
     */
    'channel' => get_env('log.channel', 'stack'),

    /*
     * The log level to use for logging.
     * Default is 'info'.
     *
     * Available levels: emergency, alert, critical, error, warning, notice, info, debug
     */
    'level' => get_env('log.level', 'info'),

    /*
     * The maximum number of log files to keep when using daily rotation.
     * Default is 5.
     */
    'max_files' => get_env('log.max_files', 5),

    /*
     * Whether to include context information in log messages.
     * Default is true.
     */
    'include_context' => get_env('log.include_context', false),

    /*
     * The path where log files will be stored.
     * Default is 'logs/' directory in the application root.
     */
    'path' => get_env('log.path', 'logs/'),

    /*
     * The log message format.
     * Default is 'text'. Other options could be 'json', etc.
     */
    'format' => get_env('log.format', 'text'),

    /*
     * The date format to use in log messages.
     * Default is 'Y-m-d H:i:s'.
     */
    'date_format' => get_env('log.date_format', 'Y-m-d H:i:s'),

    /*
     * Whether to enable log rotation.
     * Default is false.
     */
    'rotate' => get_env('log.rotate', false),

    /*
     * The rotation strategy to use.
     * Default is 'daily'. Other options could be 'size', 'weekly', etc.
     * Note: 'size' rotation requires 'max_file_size' to be set.
     *
     * Options: daily, size, weekly, monthly
     */
    'rotation' => get_env('log.rotation', 'daily'),

    /*
     * The time interval (in seconds) for rotating logs when using time-based rotation.
     * Default is 86400 seconds (1 day).
     */
    'rotation_time' => get_env('log.rotate_interval', 86400),

    /*
     * The maximum file size (in bytes) for rotating logs when using size-based rotation.
     * Default is 10485760 bytes (10 MB).
     */
    'max_file_size' => get_env('log.max_size', 10485760),

    'channels' => [

        'app' => [
            'type' => 'text',
            'path' => 'app'
        ],

        'security' => [
            'type' => 'json',
            'path' => 'security',
            'pretty_print' => false,
        ],
    ],
];
