<?php

namespace Biboletin\Mvc\Core;

use Biboletin\Mvc\Core\Exceptions\InvalidControllerException;
use Biboletin\Mvc\Core\Exceptions\InvalidHttpMethodException;
use Biboletin\Mvc\Core\Interfaces\InterfaceRouter;
use Biboletin\Mvc\Traits\HttpTrait;

/**
 * BaseRouter class
 */
abstract class BaseRouter implements InterfaceRouter
{
    use HttpTrait;

    /**
     * Routes array
     *
     * @var array|string[][]
     */
    private array $routes;
    /**
     * Array of HTTP methods
     *
     * @var array|string[]
     */
    private array $methods = [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
        'HEAD',
        'OPTIONS',
    ];



    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->routes = [];
    }


    /**
     * Set route
     *
     * @param string $method
     * @param string $path
     * @param array  $controllerMethod
     *
     * @return void
     */
    public function set(string $method, string $path, array $controllerMethod): void
    {
        if (isset($controllerMethod[0])) {
            $this->routes[$method][$path]['class'] = $controllerMethod[0];
        }
        if (isset($controllerMethod[1])) {
            $this->routes[$method][$path]['method'] = $controllerMethod[1];
        }
    }

    private function extractParams(string $path, string $uri): array
    {
        $path = explode('/', $path);
        $uri = explode('/', $uri);
        $params = [];
        foreach ($path as $key => $value) {
            if (preg_match('/\{.*\}/', $value) === 1) {
                $params[] = $uri[$key];
            }
        }
        return $params;
    }

    /**
     * Group routes
     *
     * @param $route
     * @param $fn
     *
     * @return void
     */
    protected function group($route, $fn): void
    {
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function __destruct()
    {
        $this->routes = [];
    }
}
