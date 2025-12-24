<?php

namespace Bibo\Mvc\Core\Router;

final readonly class RouteDefinition
{
    public function __construct(
        public string $method,
        public string $path,
        public mixed $handler,
        public array $middleware = [],
        public ?string $name = null,
        public array $constraints = [],
        public array $defaults = [],
    ) {
    }
}
