<?php

use Bibo\Core\Base\App;
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
        static $config = null;

        if ($config === null) {
            $config = new Config();
            $config->load();
        }

        return $config->get($key, $default);
    }
}

if (!function_exists('app')) {
    /**
     * Get the application instance
     *
     * @return App
     */
    function app(): App
    {
        return new App();
    }
}


if (!function_exists('env')) {
    /**
     * Get an environment variable
     *
     * @param string $key
     * @param string|null $default
     *
     * @return mixed
     */
    function env(string $key, ?string $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}
