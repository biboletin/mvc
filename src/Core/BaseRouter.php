<?php

namespace Biboletin\Mvc\Core;

use Biboletin\Mvc\Core\Exceptions\InvalidControllerException;
use Biboletin\Mvc\Core\Exceptions\InvalidHttpMethodException;

/**
 *
 */
abstract class BaseRouter
{
    /**
     * @var array
     */
    private array $routes;
    /**
     * @var array|string[]
     */
    private array $methods = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'HEAD',
        'OPTIONS',
    ];

    /**
     *
     */
    protected const GET_METHOD = 'get';
    /**
     *
     */
    protected const POST_METHOD = 'post';
    /**
     *
     */
    protected const PUT_METHOD = 'put';
    /**
     *
     */
    protected const DELETE_METHOD = 'delete';

    /**
     *
     */
    public function __construct()
    {
        $this->routes = [];
    }


    /**
     * @param string $method
     * @param string $path
     * @param array  $class
     *
     * @return void
     * @throws InvalidHttpMethodException
     */
    protected function set(string $method, string $path, array $class): void
    {
        if (!in_array(strtoupper($method), $this->methods)) {
            throw new InvalidHttpMethodException('Unhandled HTTP method!');
        }
        $this->routes[$method][$path]['class'] = $class[0];
        $this->routes[$method][$path]['action'] = $class[1];
    }

    /**
     * @param $route
     * @param $fn
     *
     * @return void
     */
    protected function group($route, $fn): void
    {
    }
}
