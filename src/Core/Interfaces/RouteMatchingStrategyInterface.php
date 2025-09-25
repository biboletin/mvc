<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Defines the contract for route matching strategies.
 *
 * Implementations of this interface are responsible for determining
 * if a given request matches any defined routes based on the HTTP method,
 * request path, and an array of registered routes.
 */
interface RouteMatchingStrategyInterface
{
    /**
     * Main function
     * for matching routes
     *
     * @param string $method
     * @param string $path
     * @param array  $routes
     *
     * @return array|null
     */
    public function match(string $method, string $path, array $routes): ?array;
}
