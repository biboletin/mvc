<?php

namespace Bibo\Mvc\Core\Abstracts;

use Bibo\Mvc\Core\Interfaces\RouteMatchingStrategyInterface;
use Bibo\Mvc\Core\Router\MatchedRoute;

/**
 * Abstract class defining the structure for route matching strategies.
 *
 * This class provides an abstract method that must be implemented by any concrete
 * route matching strategy to determine how routes are matched based on the HTTP
 * method, path, and available routes.
 */
abstract class AbstractMatchStrategy implements RouteMatchingStrategyInterface
{
    /**
     * Matches a given HTTP method and path against a set of defined routes.
     *
     * @param string $method The HTTP method (e.g., GET, POST) to match.
     * @param string $path   The requested URI path to match.
     * @param array  $routes An array of defined routes to check against.
     *
     * @return MatchedRoute|null Returns an array containing the matched route details if a match is found, or null otherwise.
     */
    abstract public function match(string $method, string $path, array $routes): ?MatchedRoute;

    /**
     * Builds a MatchedRoute object from the given route and parameters.
     *
     * @param array $route  The route to build the MatchedRoute object from.
     * @param array $params The parameters to associate with the MatchedRoute object.
     *
     * @return MatchedRoute The built MatchedRoute object.
     */
    protected function buildMatchedRoute(
        string $method,
        array $route,
        array $params = []
    ): MatchedRoute {
        return new MatchedRoute(
            method: $method,
            path: $route['route'],
            handler: $route['handler'],
            middleware: $route['middleware'] ?? [],
            params: $params,
            name: $route['name'] ?? null,
            group: $route['group'] ?? null,
        );
    }

}
