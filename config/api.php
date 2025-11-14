<?php

/**
 * Default configuration for APIs
 * You can override these settings in your own configuration files.
 * Make sure to replace 'your_api_key' and 'your_api_secret' with actual values.
*/

return [
    /*
     * Placeholder API
     */
    'placeholder' => [
        'url' => 'https://jsonplaceholder.typicode.com',
        'key' => 'your_api_key',
        'secret' => 'your_api_secret',
        'timeout' => 30,
        'retry' => 3,
        'retry_delay' => 1000,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'params' => [
            'userId' => 1,
            'id' => 1,
        ],
        'response_format' => 'json',
        'methods' => [
            'getPost' => 'posts/{id}',
            'getPosts' => 'posts',
            'getComments' => 'comments',
            'getAlbums' => 'albums',
            'getPhotos' => 'photos',
            'getTodos' => 'todos',
            'getUsers' => 'users',
        ],
    ],

    /*
     * Cloudflare API
     */
    'cloudflare' => [
        'url' => 'https://api.cloudflare.com/client/v4',
        'key' => 'your_api_key',
        'secret' => 'your_api_secret',
        'email' => '',
    ],

    /*
     * OpenWeatherMap API
     */
    'weather' => [
        'url' => 'https://api.openweathermap.org/data/2.5/weather',
        'key' => 'your_api_key',
        'secret' => 'your_api_secret',
        'city' => 'London',
        'units' => 'metric',
        'lang' => 'en',
        'format' => 'json',
        'timeout' => 30,
        'retry' => 3,
        'retry_delay' => 1000,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'params' => [
            'q' => 'London',
            'appid' => 'your_api_key',
            'units' => 'metric',
            'lang' => 'en',
        ],
        'response_format' => 'json',
    ],

    /*
     * SSL Checker API
     */
    'sslchecker' => [
        'url' => 'https://api.sslshopper.com/sslchecker',
        'key' => 'your_api_key',
        'secret' => 'your_api_secret',
        'domain' => 'example.com',
        'timeout' => 30,
        'retry' => 3,
        'retry_delay' => 1000,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'params' => [
            'domain' => 'example.com',
            'apikey' => 'your_api_key',
        ],
        'response_format' => 'json',
    ],

    /*
     * VirusTotal API
     */
    'virustotal' => [
        'url' => 'https://www.virustotal.com/vtapi/v2',
        'key' => 'your_api_key',
        'secret' => 'your_api_secret',
        'timeout' => 30,
        'retry' => 3,
        'retry_delay' => 1000,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'params' => [
            'apikey' => 'your_api_key',
            'resource' => '',
        ],
        'response_format' => 'json',
        'methods' => [
            'url_report' => 'url/report',
            'file_report' => 'file/report',
            'ip_report' => 'ip-address/report',
            'domain_report' => 'domain/report',
            'url_scan' => 'url/scan',
            'file_scan' => 'file/scan',
        ],
    ],
];
