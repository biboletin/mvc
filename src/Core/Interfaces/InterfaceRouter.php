<?php

namespace Biboletin\Mvc\Core\Interfaces;

/**
 * Router interface
 */
interface InterfaceRouter
{
    /**
     * Set route
     *
     * @param string $method
     * @param string $path
     * @param array  $controllerMethod
     *
     * @return void
     */
    public function set(string $method, string $path, array $controllerMethod): void;
}
