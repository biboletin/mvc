<?php

use Bibo\Mvc\Core\Application\App;
use Bibo\Mvc\Core\Config\ConfigHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

if (!function_exists('config')) {
    /**
     * Get a configuration value
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    function config(string $key, mixed $default = null): mixed
    {
        global $app;

        $envValue = get_env($key);
        if ($envValue !== null) {
            return $envValue;
        }


        try {
            $config = $app->get(ConfigHandler::class);

            return $config->get($key, $default);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            return $default;
        }
    }
}

if (!function_exists('get_env')) {
    /**
     * Get an environment variable
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    function get_env(string $key, mixed $default = null): mixed
    {
        // Transform dot-notation to UPPER_CASE_KEY
        $segments = explode('.', $key);
        $envKey = strtoupper(implode('_', $segments));

        // First, look at the environment variables
        $value = $_ENV[$envKey]
            ?? $_SERVER[$envKey]
            ?? getenv($envKey);

        if (empty($value)) {
            return $default;
        }

        return match (strtolower((string) $value)) {
            'true', '(true)'   => true,
            'false', '(false)' => false,
            'null', '(null)'   => null,
            'empty', '(empty)' => '',
            default            => $value,
        };
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
        global $app;

        return $app;
    }
}
