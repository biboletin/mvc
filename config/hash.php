<?php

/**
 * * configuration for hashing in the application.
 *
 * This configuration file defines settings for hashing algorithms and options.
 * It allows customization of how passwords and other sensitive data are hashed.
 */

return [

    /*
     * Hashing configuration.
     *
     * This section contains settings related to hashing algorithms and options.
     * It can be customized based on the application's security requirements.
     */

    'hash' => [

        /*
         * Hash algorithm used for hashing.
         * This defines the hashing algorithm to be used, such as 'bcrypt', 'argon2i', etc.
         * It can be set through environment variables or configuration files.
         */
        'algorithm' => get_env('hash_algorithm', 'bcrypt'),

        /*
         * Salt used for hashing.
         * This is an additional value that is combined with the password to enhance security.
         * It can be set through environment variables or configuration files.
         */
        'salt' => get_env('hash_salt', 'default_salt'),

        /*
         * Cost factor for hashing.
         * This defines the computational cost of the hashing process,
         * making it more secure against brute-force attacks.
         * It can be set through environment variables or configuration files.
         */
        'cost' => get_env('hash_cost', 10),

        /*
         * Rounds for hashing.
         * This defines the number of iterations for the hashing algorithm, increasing security.
         * It can be set through environment variables or configuration files.
         */
        'rounds' => get_env('hash_rounds', 10),

        /*
         * Options for the hashing algorithm.
         * This can include additional parameters specific to the chosen algorithm.
         */
        'options' => get_env('hash_options', null) ?? [],
    ],
];
