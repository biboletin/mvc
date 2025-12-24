<?php

namespace Bibo\Mvc\Core\Strategies\Router;

use Bibo\Mvc\Core\Abstracts\AbstractMatchStrategy;
use Bibo\Mvc\Core\Router\MatchedRoute;

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
     * @param array $routes
     *
     * @return MatchedRoute|null
     */
    public function match(string $method, string $path, array $routes): ?MatchedRoute
    {
        foreach ($routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $route['route'];

            if (!isset($this->cache[$pattern])) {
                $this->cache[$pattern] = $this->convertRouteToRegex($pattern);
            }

            if (preg_match($this->cache[$pattern], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                return $this->buildMatchedRoute($method, $route, $params);
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
