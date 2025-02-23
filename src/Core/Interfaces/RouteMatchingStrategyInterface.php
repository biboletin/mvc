<?php

namespace Bibo\Core\Interfaces;

/**
 * RouteMatchingStrategy interface
 */
interface RouteMatchingStrategyInterface
{
    /**
     * Main function
     * for matching routes
     *
     * @param string     $routePattern
     * @param string     $uri
     * @param array|null $params
     *
     * @return bool
     */
    public function matches(string $routePattern, string $uri, ?array &$params = null): bool;
}
