<?php

namespace Bibo\Core\Interfaces;

/**
 * RouteMatchingStrategy interface
 */
interface RouteMatchingStrategy
{
    /**
     * Match the route with the request uri
     * and return the matched route
     * and the parameters
     *
     * @param string $method
     * @param string $path
     * @param array  $routes
     *
     * @return array|null
     */
    public function match(string $method, string $path, array $routes): ?array;
}