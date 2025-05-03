<?php

/**
 * Log config file
 */

return [

    'log' => [

        'level' => $_ENV['APP_LOG_LEVEL'] ?? 'error',

        'path' => $_ENV['LOG_PATH'] ?? 'app/',

        'rotation' => $_ENV['LOG_ROTATION'] ?? 'daily',

        'format' => $_ENV['LOG_FORMAT'] ?? 'text',

        'max_file_size' => $_ENV['LOG_MAX_SIZE'] ?? 0,
    ],
];
