<?php

/**
 * configuration for encryption and hashing in the application.
 *
 * This configuration file defines settings for encryption and hashing algorithms,
 * including keys, ciphers, salts, costs, rounds, and other parameters.
 * It allows customization of how sensitive data is encrypted and hashed.
 */

return [

    /*
     * Encryption configuration.
     *
     * This section contains settings related to encryption, including keys, ciphers,
     * salts, costs, rounds, and other parameters. It can be customized based on the
     * application's security requirements.
     */

    'encryption' => [

        /*
         * Key used for encryption.
         * This should be a secure key that is kept secret and not hard-coded in the source code.
         * It can be set through environment variables or configuration files.
         */
        'key' => get_env('encryption_key', 'default_key'),

        /*
         * Cipher used for encryption.
         * This defines the encryption algorithm to be used, such as 'aes-256-cbc'.
         * It can be set through environment variables or configuration files.
         */
        'cipher' => get_env('encryption_cipher', 'aes-256-cbc'),

        /*
         * Salt used for encryption.
         * This is an additional value that is combined with the key to enhance security.
         * It can be set through environment variables or configuration files.
         */
        'salt' => get_env('encryption_salt', 'default_salt'),

        /*
         * Cost factor for encryption.
         * This defines the computational cost of the encryption process,
         * making it more secure against brute-force attacks.
         * It can be set through environment variables or configuration files.
         */
        'cost' => get_env('encryption_cost', 12),

        /*
         * Rounds for encryption.
         * This defines the number of iterations for the encryption algorithm, increasing security.
         * It can be set through environment variables or configuration files.
         */
        'rounds' => get_env('encryption_rounds', 10),

        /*
         * Initialization vector length for encryption.
         * This defines the length of the initialization vector used in the encryption process.
         * It can be set through environment variables or configuration files.
         */
        'iv_length' => get_env('encryption_iv_length', 16),

        /*
         * Use HMAC for encryption.
         * This setting determines whether to use HMAC (Hash-based Message Authentication Code) for additional security.
         * It can be set through environment variables or configuration files.
         */
        'use_hmac' => get_env('encryption_use_hmac', true),
    ],
];
