<?php

/**
 * Default cookie configuration
 */

return [

    /*
     * Cookie name
     */

    'name' => get_env('cookie.name', 'cookie_'),

    /*
     * Cookie prefix
     */

    'prefix' => get_env('cookie.prefix', ''),

    /*
     * Cookie expiration time
     */

    'expire' => get_env('cookie.expire', 3600),

    /*
     * Cookie path
     */

    'path' => get_env('cookie.path', '/'),

    /*
     * Cookie domain
     */

    'domain' => get_env('cookie.domain', $_SERVER['HTTP_HOST']),

    /*
     * Cookie secure flag
     */

    'secure' => get_env('cookie.secure', 0),

    /*
     * Cookie HTTP only flag
     */

    'httponly' => get_env('cookie.httponly', 0),

    /*
     * Cookie same site attribute
     */

    'samesite' => get_env('cookie.samesite', 'lax'),

    /*
     * Cookie encryption
     */

    'encrypted' => get_env('cookie.encrypted', false),

    /*
     * Cookie priority
     * Values can be: Low, Medium or High
     */

    'priority' => get_env('cookie.priority', 'Low'),

    /*
     * Cookie partition
     */

    'partitioned' => get_env('cookie.partitioned', false),

    /*
     * Cookie Jar path
     */

    'jar_path' => get_env('cookie.jar_path'),
];
