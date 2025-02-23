<?php

namespace Bibo\Core\BaseRouter;

use Bibo\Core\Interfaces\RouteMatchingStrategyInterface;

/**
 * Match strategy class
 */
abstract class AbstractMatchStrategy implements RouteMatchingStrategyInterface
{
    /**
     * Match route
     *
     * @param string $method
     * @param string $path
     * @param array  $routes
     *
     * @return array|null
     */
    abstract public function match(string $method, string $path, array $routes): ?array;
}
