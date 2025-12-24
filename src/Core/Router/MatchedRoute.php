<?php

namespace Bibo\Mvc\Core\Router;

/**
 * MatchedRoute class definition.
 */
final readonly class MatchedRoute
{
    /**
     * MatchedRoute constructor.
     *
     * @param string $method
     * @param string $path
     * @param mixed $handler
     * @param array $middleware
     * @param array $params
     * @param string|null $name
     * @param string|null $group
     *
     * @return void
     */
    public function __construct(
        private string $method,
        private string $path,
        private mixed $handler,
        private array $middleware = [],
        private array $params = [],
        private ?string $name = null,
        private ?string $group = null,
    ) {
    }

    /**
     * Get matched route method
     *
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get matched route path
     *
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Get matched route handler
     *
     * @return mixed
     */
    public function handler(): mixed
    {
        return $this->handler;
    }

    /**
     * Get matched route middleware
     *
     * @return array
     */
    public function middleware(): array
    {
        return $this->middleware;
    }

    /**
     * Get matched route params
     *
     * @return array
     */
    public function params(): array
    {
        return $this->params;
    }

    /**
     * Get matched route name
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * Get matched route group
     *
     * @return string|null
     */
    public function group(): ?string
    {
        return $this->group;
    }
}
