<?php

namespace Bibo\Mvc\Core\Router;

use Bibo\Mvc\Core\Interfaces\RouteMatchingStrategyInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class RouteMatcher
{
    public function __construct(
        private RouteCollection $routes,
        private RouteMatchingStrategyInterface $strategy
    ) {
    }

    public function match(ServerRequestInterface $request): ?MatchedRoute
    {
        return $this->strategy->match(
            $request->getMethod(),
            $request->getUri()->getPath(),
            $this->routes->all()
        );
    }
}
