<?php

/**
 * Helper functions
 */

use Core\Config;
use Core\View;

/**
 * @param string $param
 * @return string
 */
function config(string $param): string
{
    return Config::get($param);
}

/**
 * @param string $path
 */
function assets(string $path): string
{
    $url = config('app.url');
    return $url . $path;
}

/**
 * @param string $view
 * @param array $data
 *
 * @return string
 */
function view(string $view, array $data = []): string
{
    return (new View())->set($view, $data);
}

function server(string $key): string
{
    return $_SERVER[strtoupper(trim(strip_tags($key)))] ?? '';
}

function route(string $route): string
{
    return config('app.url') . $route;
}
