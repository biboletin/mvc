<?php

namespace Bibo\Mvc\Core\Strategies\Router;

use Bibo\Mvc\Core\Abstracts\AbstractMatchStrategy;
use Bibo\Mvc\Core\Router\MatchedRoute;

/**
 * Route matching strategy that only matches exact method and path pairs.
 */
class ExactMatchStrategy extends AbstractMatchStrategy
{
    /**
     * Attempt to find a route whose HTTP method and path exactly match.
     *
     * @param string $method The incoming HTTP method.
     * @param string $path The request path (already normalized).
     * @param array<int, array> $routes The list of registered routes.
     *
     * @return MatchedRoute|null The matched route definition or null if none matched.
     */
    public function match(string $method, string $path, array $routes): ?MatchedRoute
    {
        foreach ($routes as $route) {
            if ($route['route'] === $path) {
                return $this->buildMatchedRoute($method, $route);
            }
        }
        return null;
    }
}
