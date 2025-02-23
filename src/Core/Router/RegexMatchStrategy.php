<?php

namespace Bibo\Core\BaseRouter;

use Bibo\Core\Interfaces\RouteMatchingStrategy;

class RegexMatchStrategy implements RouteMatchingStrategy
{
    /**
     * Match regex routes
     *
     * @param string $method
     * @param string $path
     * @param array  $routes
     *
     * @return array|null
     */
    public function match(string $method, string $path, array $routes): ?array
    {
        foreach ($routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $path)) {
                return $route;
            }
        }
        return null;
    }
}
