<?php

/**
 * Default configuration for API
 */

return [
    /*
     * API version
     */
    'api' => [
        'version' => '1.0',
        'prefix' => '/api',
        'middleware' => [
            'Biboletin\Middleware\ExampleMiddleware'
        ]
    ],
    /*
     * API response
     */
    'redirect' => [
        'class' => 'Biboletin\Response\RedirectResponse',
        'arguments' => [
            'url' => 'http://example.com',
            'statusCode' => 200,
            'headers' => []
        ]
    ],
];
