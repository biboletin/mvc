<?php

/**
 * Security configuration
 */

return [

    'security' => [

        /*
         * Encryption key
         */

        'key' => 'your-secret-key',

        /*
         * Encryption cipher
         */

        'cipher' => 'AES-256-CBC',

        /*
         * Hash algorithm
         */

        'hash' => 'sha256',

        /*
         * Hash key
         */

        'hash_key' => 'your-hash-key',

        /*
         * Hash algo
         */

        'hash_algo' => 'sha256',

        /*
         * Hash algorithm options
         */

        'hash_options' => [
            'cost' => 12,
        ],

        /*
         * CSRF token
         */

        'csrf' => [
            'key' => 'csrf-token',
            'expires' => 3600,
        ],

        /*
         * CORS
         */

        'cors' => [
            'enabled' => false,
            'origin' => '*',
            'methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
            'headers' => ['Content-Type', 'Authorization'],
            'exposed' => [],
            'max_age' => 0,
        ],

        /*
         * JWT
         */

        'jwt' => [
            'enabled' => false,
        ],

        /*
         * Recaptcha
         */

        'recaptcha' => [
            'enabled' => false,
            'site_key' => '',
        ],

        /*
         * Encryption
         */

        'encryption' => [
            'enabled' => false,
            'key' => 'your-encryption-key',
            'cipher' => 'AES-256-CBC',
        ],

        /*
         * Hashids
         */

        'hashids' => [
            'enabled' => false,
            'salt' => 'your-salt',
            'length' => 10,
        ],

        /*
         * Password
         */

        'password' => [
            'algorithm' => PASSWORD_DEFAULT,
            'options' => [
                'cost' => 12,
            ],
        ],

        /*
         * OTP
         */

        'otp' => [
            'length' => 6,
            'ttl' => 300,
        ],

        /*
         * Auth
         */

        'auth' => [
            'enabled' => false,
            'model' => 'App\Models\User',
            'field' => 'email',
            'password' => 'password',
            'remember' => 'remember_token',
            'otp' => 'otp',
            'jwt' => 'jwt',
            'hash' => 'hash',
            'hash_algo' => 'sha256',
            'hash_options' => [
                'cost' => 12,
            ],
        ],

        /*
         * Security
         */

        'session' => [],

        /*
         * Cookies
         */

        'cookies' => [],

        /*
         * Security headers
         */

        'headers' => [
            'x-frame-options' => 'DENY',
            'x-xss-protection' => '1; mode=block',
            'x-content-type-options' => 'nosniff',
            'referrer-policy' => 'no-referrer',
            'feature-policy' => '',
        ],

        /*
         * Content Security Policy
         */

        'content_security_policy' => [
            'enabled' => false,
            'directives' => [
                'default-src' => "'self'",
                'script-src' => "'self'",
                'style-src' => "'self'",
                'img-src' => "'self'",
                'connect-src' => "'self'",
                'font-src' => "'self'",
                'object-src' => "'none'",
                'media-src' => "'none'",
                'frame-src' => "'none'",
                'sandbox' => '',
                'base-uri' => "'self'",
                'form-action' => "'self'",
                'frame-ancestors' => "'none'",
                'manifest-src' => "'self'",
                'prefetch-src' => "'self'",
                'worker-src' => "'self'",
                'child-src' => "'self'",
                'navigate-to' => "'self'",
                'report-uri' => '',
                'block-all-mixed-content' => false,
                'upgrade-insecure-requests' => false,
                'plugin-types' => '',
                'require-sri-for' => '',
                'report-to' => '',
            ],
        ],

        /*
         * Content Security Policy report
         */

        'hsts' => [
            'enabled' => false,
            'max_age' => 31536000,
            'include_subdomains' => false,
            'preload' => false,
        ],

        /*
         * Content Security Policy report
         */

        'referrer_policy' => [
            'enabled' => false,
            'policy' => 'no-referrer',
        ],

        /*
         * Feature Policy
         */

        'feature_policy' => [
            'enabled' => false,
            'directives' => [
                'accelerometer' => "'none'",
                'ambient-light-sensor' => "'none'",
                'autoplay' => "'none'",
                'camera' => "'none'",
                'document-domain' => "'none'",
                'encrypted-media' => "'none'",
                'fullscreen' => "'none'",
                'geolocation' => "'none'",
                'gyroscope' => "'none'",
                'magnetometer' => "'none'",
                'microphone' => "'none'",
                'midi' => "'none'",
                'payment' => "'none'",
                'picture-in-picture' => "'none'",
                'publickey-credentials-get' => "'none'",
                'sync-xhr' => "'none'",
                'usb' => "'none'",
                'wake-lock' => "'none'",
                'xr-spatial-tracking' => "'none'",
            ],
        ],

        /*
         * Expect-CT
         */

        'csp' => [
            'enabled' => false,
            'directives' => [
                'default-src' => "'self'",
                'script-src' => "'self'",
                'style-src' => "'self'",
                'img-src' => "'self'",
                'connect-src' => "'self'",
                'font-src' => "'self'",
                'object-src' => "'none'",
                'media-src' => "'none'",
                'frame-src' => "'none'",
                'sandbox' => '',
                'base-uri' => "'self'",
                'form-action' => "'self'",
                'frame-ancestors' => "'none'",
                'manifest-src' => "'self'",
                'prefetch-src' => "'self'",
                'worker-src' => "'self'",
                'child-src' => "'self'",
                'navigate-to' => "'self'",
                'report-uri' => '',
                'block-all-mixed-content' => false,
                'upgrade-insecure-requests' => false,
                'plugin-types' => '',
                'require-sri-for' => '',
                'report-to' => '',
            ],
        ],

        /*
         * Expect-CT report
         */

        'csp_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * Referrer Policy report
         */

        'csp_report_only' => [
            'enabled' => false,
            'directives' => [
                'default-src' => "'self'",
                'script-src' => "'self'",
                'style-src' => "'self'",
                'img-src' => "'self'",
                'connect-src' => "'self'",
                'font-src' => "'self'",
                'object-src' => "'none'",
                'media-src' => "'none'",
                'frame-src' => "'none'",
                'sandbox' => '',
                'base-uri' => "'self'",
                'form-action' => "'self'",
                'frame-ancestors' => "'none'",
                'manifest-src' => "'self'",
                'prefetch-src' => "'self'",
                'worker-src' => "'self'",
                'child-src' => "'self'",
                'navigate-to' => "'self'",
                'report-uri' => '',
                'block-all-mixed-content' => false,
                'upgrade-insecure-requests' => false,
                'plugin-types' => '',
                'require-sri-for' => '',
                'report-to' => '',
            ],
        ],

        /*
         * CSP Policy report
         */

        'csp_report_only_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * HSTS report
         */

        'referrer_policy_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * Feature Policy report
         */

        'feature_policy_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * Security Policy report
         */

        'hsts_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * Security Policy
         */

        'security_policy' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * Security Policy report
         */

        'security_policy_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * XSS Protection report
         */

        'xss_protection_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * Content Type Options report
         */

        'content_type_options_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * Frame Options report
         */

        'frame_options_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * Expect CT report
         */

        'expect_ct_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * X Content Type Options report
         */

        'x_content_type_options_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * X Frame Options report
         */

        'x_frame_options_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],

        /*
         * X XSS Protection report
         */

        'x_xss_protection_report' => [
            'enabled' => false,
            'report_uri' => '',
        ],
    ],
];
