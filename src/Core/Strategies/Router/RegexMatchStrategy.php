<?php

namespace Bibo\Mvc\Core\Strategies\Router;

use Bibo\Mvc\Core\Abstracts\AbstractMatchStrategy;
use Bibo\Mvc\Core\Router\MatchedRoute;

/**
 * Regex-based route matching strategy.
 *
 * Matches routes defined with parameters such as:
 *   /users/{id}
 *   /posts/{slug:[a-z0-9\-]+}
 *
 * This strategy does NOT cache compiled regex patterns.
 * It exists mainly for simplicity or as a fallback.
 */
class RegexMatchStrategy extends AbstractMatchStrategy
{
    /**
     * Attempt to match a route using regex patterns.
     *
     * @param string $method HTTP method
     * @param string $path   Normalized request path
     * @param array  $routes Registered route definitions
     *
     * @return MatchedRoute|null
     */
    public function match(string $method, string $path, array $routes): ?MatchedRoute
    {
        foreach ($routes as $route) {
            $regex = $this->convertRouteToRegex($route['route']);

            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->buildMatchedRoute($method, $route, $params);
            }
        }

        return null;
    }

    /**
     * Convert a route definition into a regex pattern.
     *
     * Supported syntax:
     *  - {param}
     *  - {param:custom-regex}
     *
     * @param string $route
     *
     * @return string
     */
    private function convertRouteToRegex(string $route): string
    {
        $pattern = preg_replace_callback(
            '/\{(\w+)(?::([^}]+))?}/',
            static function (array $matches): string {
                $name  = $matches[1];
                $regex = $matches[2] ?? '[^/]+';

                return '(?P<' . $name . '>' . $regex . ')';
            },
            $route
        );

        return '#^' . $pattern . '$#';
    }
}
