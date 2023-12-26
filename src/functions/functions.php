<?php

use Biboletin\Mvc\App;
use Biboletin\Mvc\Router;
use Biboletin\Mvc\View;

/**
 * Sanitize global variables
 *
 * @param array $variable
 *
 * @return array
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
 * @param string $route
 * @param ...$params
 *
 * @return void
 */
function route(string $route, ...$params): void
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
