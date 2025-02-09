<?php

namespace Bibo\Core\Facades;

use Bibo\Core\Router\BaseRouter;

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
     *
     * @return BaseRouter
     */
    public static function get(string $path, array|callable $handler): BaseRouter
    {
        return self::getInstance()->get($path, $handler);
    }

    /**
     * Set POST route
     *
     * @param string         $path
     * @param array|callable $handler
     *
     * @return BaseRouter
     */
    public static function post(string $path, array|callable $handler): BaseRouter
    {
        return self::getInstance()->post($path, $handler);
    }

    /**
     * Set PUT route
     *
     * @param string         $path
     * @param array|callable $handler
     *
     * @return BaseRouter
     */
    public static function put(string $path, array|callable $handler): BaseRouter
    {
        return self::getInstance()->put($path, $handler);
    }

    /**
     * Set DELETE route
     *
     * @param string         $path
     * @param array|callable $handler
     *
     * @return BaseRouter
     */
    public static function delete(string $path, array|callable $handler): BaseRouter
    {
        return self::getInstance()->delete($path, $handler);
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
     * @return array|null
     */
    public static function match(string $method, string $uri): ?array
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
