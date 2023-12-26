<?php

use Biboletin\Mvc\App;
use Biboletin\Mvc\Config;
use Biboletin\Mvc\Router;
use Biboletin\Mvc\View;

/**
 * Sanitize global variables
 *
 * @param array<string> $variable
 *
 * @return array<string>
 */
function sanitizeGlobalVariable(array $variable): array
{
    if (empty(array_filter($variable)) === true) {
        return [];
    }

    return array_map(function ($value) {
        $trim = trim($value);
        $strip = strip_tags($trim);

        return htmlspecialchars($strip);
    }, $variable);
}

/**
 * @param string $view
 *
 * @return void
 */
function view(string $view): void
{
    $view = new View();
}

/**
 * @param string      $route
 * @param string|null ...$params
 *
 * @return void
 */
function route(string $route, ?string ...$params): void
{
    $router = new Router();
}

/**
 * @return App
 */
function app(): App
{
    return new App();
}

/**
 * @param string      $key
 * @param string|null $default
 *
 * @return string
 */
function config(string $key, ?string $default = null): string
{
    $config = new Config();

    return $config->get($key, $default);
}
