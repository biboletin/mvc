<?php

namespace Bibo\Mvc\Core\Router;

use Bibo\Mvc\Core\Enums\HttpMethod;

final class RouteCollection
{
    private array $routes = [];

    /**
     * Add a route to the router.
     *
     * @param string $method HTTP method
     * @param string $path
     * @param callable|array $handler Controller or callable
     * @param array|null $middleware Optional middleware stack
     * @param string|null $name Optional route name
     *
     * @return void
     */
    public function add(
        string $method,
        string $path,
        callable|array $handler,
        ?array $middleware = null,
        ?string $name = null
    ): void {
        $upperMethod = strtoupper(trim($method));

        $this->routes[$upperMethod][] = [
            'method' => $upperMethod,
            'route' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
            'name' => $name,
        ];
    }

    /**
     * Get routes for a given HTTP method.
     *
     * @param string $method HTTP method
     *
     * @return array
     */
    public function forMethod(string $method): array
    {
        $upperMethod = strtoupper(trim($method));

        return array_merge(
            $this->routes[$upperMethod] ?? [],
            $this->routes[HttpMethod::ANY->value] ?? []
        );
    }

    /**
     * Get all routes.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->routes;
    }
}
