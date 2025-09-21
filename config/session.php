<?php

/**
* Session configuration
*/

return [

    /*
     * Session name
     */

    'name' => get_env('session.name', 'PHPSESSID'),

    /*
     * Session lifetime
     */

    'lifetime' => get_env('session.lifetime', 86400),

    /*
     * Session path
     */

    'path' => get_env('session.path', '/'),

    /*
     * Session domain
     */

    'domain' => get_env('session.domain', 'localhost'),

    /*
     * Session secure flag
     */

    'secure' => get_env('session.secure', false),

    /*
     * Session HTTP only flag
     */

    'httponly' => get_env('session.httponly', false),

    /*
     * Session same site attribute
     */

    'samesite' => get_env('session.samesite', 'lax'),

    /*
     * Session name prefix
     */

    'prefix' => get_env('session.prefix', ''),

    /*
     * Session encryption
     */

    'encrypt' => get_env('session.encrypt', false),

    /*
     * Session compression
     */

    'compress' => get_env('session.compress', false),
];
