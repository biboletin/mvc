<?php

namespace Biboletin\Mvc\Core;

abstract class BaseRouter
{
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }
}
