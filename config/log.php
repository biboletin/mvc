<?php

/**
 *
 */

return [

    'log' => [

        'path' => $_ENV['LOG_PATH'] ?? '',

        'rotation' => $_ENV['LOG_ROTATION'] ?? 'daily',

        'format' => $_ENV['LOG_FORMAT'] ?? 'text',

        'max_file_size' => $_ENV['LOG_MAX_SIZE'] ?? 0,
    ],
];