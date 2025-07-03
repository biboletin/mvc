<?php

return [

    'observers' => [

        'production' => [
            'Bibo\Logger\Observer\LogObserver',
        ],

        'development' => [
            'Bibo\Logger\Observer\LogObserver',
        ],
    ],
];
