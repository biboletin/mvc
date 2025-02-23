<?php

namespace Bibo\Core\BaseRouter;

use Bibo\Core\Interfaces\RouteMatchingStrategyInterface;

/**
 * Caching routes strategy class
 */
class CachedRegexMatchStrategy implements RouteMatchingStrategyInterface
{
    /**
     * Cache
     *
     * @var array
     */
    private array $cache = [];

    /**
     * Route matches
     *
     * @param string     $routePattern
     * @param string     $uri
     * @param array|null $params
     *
     * @return bool
     */
    public function matches(string $routePattern, string $uri, ?array &$params = null): bool
    {
        // Check if the regex is already cached
        if (!isset($this->cache[$routePattern])) {
            $this->cache[$routePattern] = $this->convertRouteToRegex($routePattern);
        }

        $regex = $this->cache[$routePattern];

        if (preg_match($regex, $uri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }

    /**
     * Convert routes to regex
     *
     * @param string $routePattern
     *
     * @return string
     */
    private function convertRouteToRegex(string $routePattern): string
    {
        return '~^' . preg_replace('/\{(\w+)}/', '(?P<$1>[^/]+)', $routePattern) . '$~';
    }
}
