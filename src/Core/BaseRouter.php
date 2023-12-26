<?php

namespace Biboletin\Mvc\Core;

abstract class BaseRouter
{
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    protected function set(string $method, string $path, $callable): void
    {
    }

    protected function group($route, $fn): void
    {
    }
}
