<?php

use Bibo\App\Middleware\CorsMiddleware;
use Bibo\App\Middleware\CsrfMiddleware;

return [
    // Global middleware
    'global' => [
        // Add your global middleware here
        'cors' => CorsMiddleware::class,
    ],

    // Middleware groups
    'groups' => [
        'web' => [
            // Add your web middleware here
        ],
        // 'api' => [
        //     // Add your API middleware here
        // ],
    ],

    // Route middleware
    'route' => [
        'csrf' => CsrfMiddleware::class,
        'cors' => CorsMiddleware::class,
        // 'auth' => [
        //     // Add your auth middleware here
        // ],
        // 'guest' => [
        //     // Add your guest middleware here
        // ],
        // 'admin' => [
        //     // Add your admin middleware here
        // ],
        // 'throttle' => [
        //     // Add your throttle middleware here
        // ],
        // 'cors' => [
        //     // Add your CORS middleware here
        // ],
        // 'logging' => [
        //     // Add your logging middleware here
        // ],
        // 'maintenance' => [
        //     // Add your maintenance middleware here
        // ],
        // 'debug' => [
        //     // Add your debug middleware here
        // ],
        // 'session' => [
        //     // Add your session middleware here
        // ],
        // 'locale' => [
        //     // Add your locale middleware here
        // ],
        // 'input' => [
        //     // Add your input middleware here
        // ],
        // 'output' => [
        //     // Add your output middleware here
        // ],
        // 'compression' => [
        //     // Add your compression middleware here
        // ],
        // 'security' => [
        //     // Add your security middleware here
        // ],
        // 'error' => [
        //     // Add your error middleware here
        // ],
        // 'response' => [
        //     // Add your response middleware here
        // ],
        // 'request' => [
        //     // Add your request middleware here
        // ],
        // 'route' => [
        //     // Add your route middleware here
        // ],
        // 'view' => [
        //     // Add your view middleware here
        // ],
        // 'cache' => [
        //     // Add your cache middleware here
        // ],
        // 'database' => [
        //     // Add your database middleware here
        // ],
        // 'queue' => [
        //     // Add your queue middleware here
        // ],
        // 'event' => [
        //     // Add your event middleware here
        // ],
        // 'notification' => [
        //     // Add your notification middleware here
        // ],
        // 'mail' => [
        //     // Add your mail middleware here
        // ],
    ],
];
