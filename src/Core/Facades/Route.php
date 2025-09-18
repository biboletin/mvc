<?php

namespace Bibo\Mvc\Core\Facades;

use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Router\BaseRouter;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Facade route class
 */
final class Route
{
    /**
     * Instance
     *
     * @var BaseRouter|null
     */
    private static ?BaseRouter $instance = null;
    private static array $middleware = [];

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
     * Set PATCH route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array          $middleware
     *
     * @return BaseRouter
     */
    public static function patch(string $path, array|callable $handler, array $middleware): BaseRouter
    {
        return self::getInstance()->patch($path, $handler, $middleware);
    }

    /**
     * Set HEAD route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array          $middleware
     *
     * @return BaseRouter
     */
    public static function head(string $path, array|callable $handler, array $middleware): BaseRouter
    {
        return self::getInstance()->head($path, $handler, $middleware);
    }

    /**
     * Set OPTIONS route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array          $middleware
     *
     * @return BaseRouter
     */
    public static function options(string $path, array|callable $handler, array $middleware): BaseRouter
    {
        return self::getInstance()->options($path, $handler, $middleware);
    }

    /**
     * Set CONNECT route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array          $middleware
     *
     * @return BaseRouter
     */
    public static function connect(string $path, array|callable $handler, array $middleware): BaseRouter
    {
        return self::getInstance()->connect($path, $handler, $middleware);
    }

    /**
     * Set TRACE route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array          $middleware
     *
     * @return BaseRouter
     */
    public static function trace(string $path, array|callable $handler, array $middleware): BaseRouter
    {
        return self::getInstance()->trace($path, $handler, $middleware);
    }

    /**
     * Set ANY route
     *
     * @param string         $path
     * @param array|callable $handler
     * @param array          $middleware
     *
     * @return BaseRouter
     */
    public static function any(string $path, array|callable $handler, array $middleware): BaseRouter
    {
        return self::getInstance()->any($path, $handler, $middleware);
    }

    /**
     * Group routes
     *
     * @param string   $name
     * @param callable $handler
     *
     * @return BaseRouter
     */
    public static function group(string $name, callable $handler): BaseRouter
    {
        return self::getInstance()->group($name, $handler);
    }

    /**
     * Redirect route
     *
     * @param string $from
     * @param string $to
     * @param int    $status
     *
     * @return BaseRouter
     */
    public static function redirect(string $from, string $to, int $status = HttpStatus::Found->value): BaseRouter
    {
        return self::getInstance()->redirect($from, $to, $status);
    }

    /**
     * Resource route
     *
     * @param string $prefix
     * @param string $controller
     *
     * @return BaseRouter
     */
    public static function resource(string $prefix, string $controller): BaseRouter
    {
        return self::getInstance()->resource($prefix, $controller);
    }

    /**
     * Match route
     *
     * @param string|array $method
     * @param string       $uri
     *
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public static function match(string|array $method, string $uri): ResponseInterface
    {
        return self::getInstance()->matchRoutes($method, $uri);
    }

    /**
     * Add middleware
     *
     * @param string|array $middleware
     *
     * @return void
     */
    public function middleware(string|array $middleware): void
    {
        // dd(self::$instance);
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
    public function name(string $name): void
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
