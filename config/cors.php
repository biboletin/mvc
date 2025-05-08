<?php

/**
 * CORS Middleware Configuration
 *
 * This file contains the configuration for the CORS middleware.
 * It defines the allowed origins, methods, headers, and other settings.
 */

return [
    'cors' => [

        'allowed_origins' => ['*'], // Allow all origins

        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], // Allowed HTTP methods

        'allowed_headers' => ['Content-Type', 'Authorization'], // Allowed headers

        'exposed_headers' => [], // Exposed headers

        'max_age' => 3600, // Max age for preflight requests

        'supports_credentials' => true, // Support credentials (cookies, authorization headers)

        'allow_any_origin' => true, // Allow any origin

        'allow_any_method' => true, // Allow any method

        'allow_any_header' => true, // Allow any header

        'allow_any_expose_header' => true, // Allow any exposed header

        'allow_any_max_age' => true, // Allow any max age

        'allow_any_supports_credentials' => true, // Allow any supports credentials

        'allow_any_origin_pattern' => false, // Allow any origin pattern

        'allow_any_method_pattern' => false, // Allow any method pattern

        'allow_any_header_pattern' => false, // Allow any header pattern

        'allow_any_expose_header_pattern' => false, // Allow any exposed header pattern

        'allow_any_max_age_pattern' => false, // Allow any max age pattern

        'allow_any_supports_credentials_pattern' => false, // Allow any supports credentials pattern

        'allow_any_origin_regex' => false, // Allow any origin regex

        'allow_any_method_regex' => false, // Allow any method regex

        'allow_any_header_regex' => false, // Allow any header regex

        'allow_any_expose_header_regex' => false, // Allow any exposed header regex

        'allow_any_max_age_regex' => false, // Allow any max age regex

        'allow_any_supports_credentials_regex' => false, // Allow any supports credentials regex

        'allow_any_origin_callback' => null, // Allow any origin callback

        'allow_any_method_callback' => null, // Allow any method callback

        'allow_any_header_callback' => null, // Allow any header callback

        'allow_any_expose_header_callback' => null, // Allow any exposed header callback

        'allow_any_max_age_callback' => null, // Allow any max age callback

        'allow_any_supports_credentials_callback' => null, // Allow any supports credentials callback

        'allow_any_origin_pattern_callback' => null, // Allow any origin pattern callback

        'allow_any_method_pattern_callback' => null, // Allow any method pattern callback

        'allow_any_header_pattern_callback' => null, // Allow any header pattern callback

        'allow_any_expose_header_pattern_callback' => null, // Allow any exposed header pattern callback

        'allow_any_max_age_pattern_callback' => null, // Allow any max age pattern callback

        'allow_any_supports_credentials_pattern_callback' => null, // Allow any supports credentials pattern callback

        'allow_any_origin_regex_callback' => null, // Allow any origin regex callback

        'allow_any_method_regex_callback' => null, // Allow any method regex callback

        'allow_any_header_regex_callback' => null, // Allow any header regex callback

        'allow_any_expose_header_regex_callback' => null, // Allow any exposed header regex callback

        'allow_any_max_age_regex_callback' => null, // Allow any max age regex callback

        'allow_any_supports_credentials_regex_callback' => null, // Allow any supports credentials regex callback

        'allow_any_origin_callback_regex' => null, // Allow any origin callback regex

        'allow_any_method_callback_regex' => null, // Allow any method callback regex

        'allow_any_header_callback_regex' => null, // Allow any header callback regex

        'allow_any_expose_header_callback_regex' => null, // Allow any exposed header callback regex

        'allow_any_max_age_callback_regex' => null, // Allow any max age callback regex

        'allow_any_supports_credentials_callback_regex' => null, // Allow any supports credentials callback regex

        'allow_any_origin_callback_pattern' => null, // Allow any origin callback pattern

        'allow_any_method_callback_pattern' => null, // Allow any method callback pattern

        'allow_any_header_callback_pattern' => null, // Allow any header callback pattern

        'allow_any_expose_header_callback_pattern' => null, // Allow any exposed header callback pattern

        'allow_any_max_age_callback_pattern' => null, // Allow any max age callback pattern

        'allow_any_supports_credentials_callback_pattern' => null, // Allow any supports credentials callback pattern

    ],

    'cors_enabled' => true, // Enable CORS middleware

    'cors_debug' => false, // Enable CORS debug mode

    'cors_debug_file' => 'cors_debug.log', // CORS debug log file

    'cors_debug_file_path' => __DIR__ . '/../logs/', // CORS debug log file path

    'cors_debug_file_size' => 1048576, // CORS debug log file size (1MB)

    'cors_debug_file_max_files' => 5, // CORS debug log file max files

    'cors_debug_file_max_size' => 10485760, // CORS debug log file max size (10MB)

    'cors_debug_file_max_age' => 604800, // CORS debug log file max age (7 days)

    'cors_debug_file_max_age_pattern' => false, // CORS debug log file max age pattern

    'cors_debug_file_max_age_callback' => null, // CORS debug log file max age callback

    'cors_debug_file_max_age_callback_pattern' => null, // CORS debug log file max age callback pattern

    'cors_debug_file_max_age_callback_regex' => null, // CORS debug log file max age callback regex

    'cors_debug_file_max_age_callback_regex_pattern' => null, // CORS debug log file max age callback regex pattern

    'cors_debug_file_max_age_callback_regex_callback' => null, // CORS debug log file max age callback regex callback
];
