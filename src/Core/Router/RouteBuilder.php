<?php

namespace Bibo\Mvc\Core\Router;

final class RouteBuilder
{
    private array $middleware = [];
    private ?string $name = null;
    private array $constraints = [];
    private array $defaults = [];

    public function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly mixed $handler,
    ) {
    }

    public function middleware(string|array $middleware): self
    {
        $this->middleware = array_merge(
            $this->middleware,
            (array) $middleware
        );

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function where(string $param, string $regex): self
    {
        $this->constraints[$param] = $regex;
        return $this;
    }

    public function defaults(array $defaults): self
    {
        $this->defaults = $defaults;
        return $this;
    }

    public function build(): RouteDefinition
    {
        return new RouteDefinition(
            $this->method,
            $this->path,
            $this->handler,
            $this->middleware,
            $this->name,
            $this->constraints,
            $this->defaults,
        );
    }
}
