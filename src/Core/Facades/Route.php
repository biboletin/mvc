<?php

namespace Bibo\Mvc\Core\Facades;

use Bibo\Mvc\Core\BaseRouter\BaseRouter;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Facade route class
 */
class Route
{
    /**
     * Instance
     *
     * @var BaseRouter|null
     */
    private static ?BaseRouter $instance = null;

    public static function init(BaseRouter $router): void
    {
        self::$instance = $router;
    }
    /**
     * Creates new instance
     *
     * @return BaseRouter
     */
    protected static function getInstance(): BaseRouter
    {
        if (self::$instance === null) {
            self::$instance = new BaseRouter();
        }
        return self::$instance;
    }

    /**
     * Set GET route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array|null     $middleware
     *
     * @return BaseRouter
     */
    public static function get(string $path, array|callable $handler, ?array $middleware = null): BaseRouter
    {
        return self::getInstance()->get($path, $handler, $middleware);
    }

    /**
     * Set POST route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array          $middleware
     *
     * @return BaseRouter
     */
    public static function post(string $path, array|callable $handler, array $middleware): BaseRouter
    {
        return self::getInstance()->post($path, $handler, $middleware);
    }

    /**
     * Set PUT route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array          $middleware
     *
     * @return BaseRouter
     */
    public static function put(string $path, array|callable $handler, array $middleware): BaseRouter
    {
        return self::getInstance()->put($path, $handler, $middleware);
    }

    /**
     * Set DELETE route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array          $middleware
     *
     * @return BaseRouter
     */
    public static function delete(string $path, array|callable $handler, array $middleware): BaseRouter
    {
        return self::getInstance()->delete($path, $handler, $middleware);
    }

    /**
     * Group routes
     *
     * @param string         $name
     * @param array|callable $handler
     *
     * @return void
     */
    public static function group(string $name, array|callable $handler): void
    {
        self::getInstance()->group($name, $handler);
    }

    /**
     * Match route
     *
     * @param string $method
     * @param string $uri
     *
     * @return ResponseInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public static function match(string $method, string $uri): ResponseInterface
    {
        return self::getInstance()->match($method, $uri);
    }

    /**
     * Add middleware
     *
     * @param callable $middleware
     *
     * @return void
     */
    public static function middleware(callable $middleware): void
    {
        self::getInstance()->middleware($middleware);
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
        return self::getInstance()->find($method, $route);
    }

    /**
     * Set route name
     *
     * @param string $name
     *
     * @return void
     */
    public static function name(string $name): void
    {
        self::getInstance()->name($name);
    }

    /**
     * Get routes
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::getInstance()->getRoutes();
    }

    /**
     * Dump routes as string
     *
     * @return void
     */
    public static function dump(): void
    {
        self::getInstance()->dump();
    }
}
