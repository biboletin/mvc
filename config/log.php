<?php

/**
 * Log config file
 */

return [

    'log' => [

        'level' => config('app_log_level', 'error'),

        'path' => config('log_path', 'app/'),

        'rotation' => config('log_rotation', 'daily'),

        'format' => config('log_format', 'text'),

        'max_file_size' => config('log_max_size', 1024),
    ],
];
