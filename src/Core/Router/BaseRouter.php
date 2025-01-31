<?php

namespace Bibo\Core\BaseRouter;

use Bibo\Core\Interfaces\RouterInterface;

use function array_find;

/**
 * BaseRouter class that handle route definitions
 */
class BaseRouter implements RouterInterface
{
    /**
     * Route container
     *
     * @var array
     */
    private array $routes = [];
    /**
     * Middleware container
     *
     * @var array
     */
    private array $middleware = [];
    /**
     * Current route group
     *
     * @var string
     */
    private string $currentRouteGroup = '';

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Add route to container
     *
     * @param string         $method
     * @param string         $route
     * @param array|callable $callback
     *
     * @return void
     */
    private function add(string $method, string $route, array|callable $callback): void
    {
        $fullRoute = ($this->currentRouteGroup ? '/' . $this->currentRouteGroup : '')
        . '/' . trim($route, '/');
        $this->routes[] = [
            'method' => $method,
            'route' => $fullRoute,
            'handler' => $callback,
        ];
    }
    /**
     * Set GET routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function get(string $route, callable|array $callable): RouterInterface
    {
        $this->add('GET', $route, $callable);
        return $this;
    }

    /**
     * Set POST routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function post(string $route, callable|array $callable): RouterInterface
    {
        $this->add('POST', $route, $callable);
        return $this;
    }

    /**
     * Set PUT routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function put(string $route, callable|array $callable): RouterInterface
    {
        $this->add('PUT', $route, $callable);
        return $this;
    }

    /**
     * Set DELETE routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function delete(string $route, callable|array $callable): RouterInterface
    {
        $this->add('DELETE', $route, $callable);
        return $this;
    }

    /**
     * Group routes
     *
     * @param string   $name
     * @param callable $callable
     *
     * @return void
     */
    public function group(string $name, callable $callable): void
    {
        $previousRouteGroup = $this->currentRouteGroup;
        $this->currentRouteGroup = rtrim($previousRouteGroup . '/' . trim($name, '/'), '/');
        $callable($this);
        $this->currentRouteGroup = $previousRouteGroup;
    }

    /**
     * Match route
     *
     * @param string $method
     * @param string $uri
     *
     * @return array|null
     */
    public function match(string $method, string $uri): ?array
    {
        return array_find(
            $this->routes,
            fn (array $route) => $route['method'] === $method && preg_match('#^' . $route['route'] . '$#', $uri)
        );
    }

    /**
     * Add middleware
     *
     * @param callable $middleware
     *
     * @return void
     */
    public function middleware(callable $middleware): void
    {
        $lastRouteKey = array_key_last($this->routes);
        if ($lastRouteKey !== null) {
            $this->routes[$lastRouteKey]['middleware'] = $middleware;
        }
    }

    /**
     * Find route
     *
     * @param string $method
     * @param string $route
     *
     * @return array|null
     */
    public function find(string $method, string $route): ?array
    {
        return array_find(
            $this->routes,
            fn (array $router) => $router['method'] === strtoupper($method) && $router['route'] === $route
        );
    }

    /**
     * Set route name
     *
     * @param string $name
     *
     * @return void
     */
    public function name(string $name): void
    {
        $lastRouteKey = array_key_last($this->routes);
        if ($lastRouteKey !== null) {
            $this->routes[$lastRouteKey]['name'] = $name;
        }
    }

    /**
     * Dump routes
     *
     * @return void
     */
    public function dump(): void
    {
        foreach ($this->routes as $route) {
            echo sprintf(
                "[%s] %s -> %s, Name: %s, Middleware: %s <br>\n",
                $route['method'],
                $route['route'],
                is_callable($route['handler']) ? 'callable' : json_encode($route['handler']),
                $route['name'] ?? 'No name',
                isset($route['middleware']) ? count($route['middleware']) . ' middleware(s)' : 'No middleware'
            );
        }
    }
}
