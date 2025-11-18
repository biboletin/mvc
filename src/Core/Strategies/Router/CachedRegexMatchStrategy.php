<?php

namespace Bibo\Mvc\Core\Strategies\Router;

use Bibo\Mvc\Core\Abstracts\AbstractMatchStrategy;

/**
 * Cached regex route matching class
 */
class CachedRegexMatchStrategy extends AbstractMatchStrategy
{
    /**
     * Cache of compiled regex patterns
     *
     * @var array<string, string>
     */
    private array $cache = [];

    /**
     * Match regex routes with caching
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
            if ($route['method'] !== $method) {
                continue;
            }

            $routePattern = $route['route'];

            // Cache compiled regex
            if (!isset($this->cache[$routePattern])) {
                $this->cache[$routePattern] = $this->convertRouteToRegex($routePattern);
            }

            $regex = $this->cache[$routePattern];

            if (preg_match($regex, $path, $matches)) {
                return [
                    'handler' => $route['handler'],
                    'params'  => array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY),
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
        return '~^' . preg_replace('/\{([\w]+)\}/', '(?P<$1>[^/]+)', $route) . '$~';
    }
}
