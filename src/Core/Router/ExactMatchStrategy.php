<?php

namespace Bibo\Core\BaseRouter;

/**
 * Simple route matching class
 */
abstract class ExactMatchStrategy extends AbstractMatchStrategy
{
    /**
     * Simple route matching
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
            if ($route['method'] === $method && $route['route'] === $path) {
                return $route;
            }
        }
        return null;
    }
}
