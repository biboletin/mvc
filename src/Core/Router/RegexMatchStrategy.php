<?php

namespace Bibo\Mvc\Core\Router;

/**
 * Regex match routes class
 */
class RegexMatchStrategy extends AbstractMatchStrategy
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
            $routePattern = $this->convertRouteToRegex($route['route']);

            if ($route['method'] === $method && preg_match($routePattern, $path, $matches)) {
                return [
                    'handler' => $route['handler'],
                    'params' => array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY),
                ];
            }
        }

        return null;
    }

    /**
     * Convert route to regex
     *
     * @param string $route
     *
     * @return string
     */
    private function convertRouteToRegex(string $route): string
    {
        return '#^' . preg_replace('/\{([\w]+)\}/', '(?P<\1>[^/]+)', $route) . '$#';
    }
}
