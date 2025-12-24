<?php

/**
 * Configuration for error handling in the application.
 *
 * This configuration file defines settings for error reporting, logging, and display.
 * It allows customization of how errors are handled based on the environment and application needs.
 */

return [

    /*
     * Error handling configuration.
     *
     * This section contains settings related to error reporting, logging, and display.
     * It can be customized based on the application's environment and requirements.
     */

    'error' => [

        /*
         * Enable or disable error reporting.
         * If set to true, errors will be reported according to the 'reporting' setting.
         * If set to false, no errors will be reported.
         */

        'reporting_enabled' => get_env('error_reporting_enabled', true),

        /*
         * Error reporting settings.
         * This defines the level of errors that will be reported.
         * It can be set to a specific error level or a combination of levels.
         */

        'reporting' => get_env('error_reporting', E_ALL & ~E_DEPRECATED),

        /*
         * Display settings for errors.
         * If set to true, errors will be displayed in the browser.
         * If set to false, errors will not be displayed, but may still be logged.
         * This is typically set to true in development environments and false in production.
         */

        'display' => get_env('error_display', true),

        /*
         * Log settings for errors.
         * This section defines how errors are logged, including the log path, level, format, and date format.
         * It also specifies the email addresses for reporting errors.
         */

        'log_path' => get_env('error_log_path', ROOT_PATH . 'storage/logs/'),

        /*
         * Log level for error logging.
         * This defines the minimum level of errors that will be logged.
         * Common levels include 'debug', 'info', 'notice', 'warning', 'error', 'critical', and 'alert'.
         */

        'log_level' => get_env('error_log_level', 'error'),

        /*
         * Log format for error logging.
         * This defines the format of log messages, including placeholders for
         * date, channel, level name, message, context, and extra information.
         */

        'log_format' => get_env('error_log_format', '[%datetime%] %channel%.%level_name%: %message% %context% %extra%'),

        /*
         * Date format for error logging.
         * This defines the format of the date in log messages.
         * It can be set to a standard PHP date format string.
         */

        'log_date_format' => get_env('error_log_date_format', 'Y-m-d H:i:s'),

        /*
         * Email settings for error reporting.
         * This section defines the email addresses used for reporting errors.
         * It includes the 'from' address and the 'to' address.
         * The 'from' address is typically the application's email address,
         * while the 'to' address is where error reports are sent.
         */

        'report_from' => get_env('error_report_from', ''),

        /*
         * Email address to which error reports are sent.
         * This is typically set to the application's support or admin email address.
         * It allows for centralized error reporting and monitoring.
         */

        'report_to' => get_env('error_report_to', ''),
    ],
];
