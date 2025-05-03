<?php

use Bibo\Core\Config\Config;

if (!function_exists('config')) {
    /**
     * Get a configuration value
     *
     * @param string $key
     * @param string|null $default
     *
     * @return mixed
     */
    function config(string $key, ?string $default = null): mixed
    {
        return Config::get($key, $default);
    }
}
