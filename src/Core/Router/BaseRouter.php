<?php

namespace Bibo\Core\BaseRouter;

use Bibo\Core\Interfaces\RouterInterface;
use Bibo\Core\Request\Stream;
use Bibo\Core\Response\BaseResponse;
use Exception;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Bibo\Core\Response\HtmlResponse;
use Bibo\Core\Response\JsonResponse;

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
     * @return ResponseInterface
     * @throws Exception
     */
    public function match(string $method, string $uri): ResponseInterface
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            $routePattern = $this->convertRouteToRegex($route['route']);

            if ($route['method'] === $method && preg_match($routePattern, $uri, $matches)) {
                // Extract parameters (remove numeric keys from matches)
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $handler = $route['handler'];

                // Handle Closure or Callable function
                if (is_callable($handler)) {
                    return $this->handleCallable($handler, $params);
                }

                // Handle Controller Actions [Controller::class, 'method']
                if (is_array($handler) && count($handler) === 2 && is_string($handler[0]) && is_string($handler[1])) {
                    return $this->handleController($handler, $params);
                }
            }
        }

        throw new Exception('Route not found', 404);
    }

    private function convertRouteToRegex(string $route): string
    {
        return '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route) . '$#';
    }


    /**
     * Handles callables and wraps responses properly
     *
     * @throws JsonException
     */
    private function handleCallable(callable $handler, array $params = []): ResponseInterface
    {
        ob_start(); // Capture echoed output
        $response = call_user_func_array($handler, $params);
        $output = ob_get_clean(); // Get the output

        // If handler returns a ResponseInterface, return it
        if ($response instanceof ResponseInterface) {
            return $response;
        }

        // If handler echoes output, wrap it in a Response
        if (!empty($output)) {
            return new HtmlResponse($output); // Ensure you have an HtmlResponse class
        }

        // Default to an empty JSON response if nothing is returned
        return new JsonResponse(['message' => 'No content'], 200);
    }


    private function handleController(array $handler, array $params = []): ResponseInterface
    {
        [$controller, $method] = $handler;

        if (!class_exists($controller)) {
            throw new Exception("Controller $controller not found", 500);
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new Exception("Method $method not found in $controller", 500);
        }

        return call_user_func_array([$instance, $method], $params);
    }


    /**
     * Creates a stream from a string
     */
    private function createStream(string $content): Stream
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write($content);
        $stream->rewind();
        return $stream;
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
     * Get routes
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
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
