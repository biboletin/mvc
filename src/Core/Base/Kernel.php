<?php

namespace Bibo\Mvc\Core\Base;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Kernel
 *
 * @package Bibo\Core\Base
 */
class Kernel
{
    /**
     * Middleware array
     *
     * @var array
     */
    protected array $middleware = [];

    /**
     * Kernel constructor.
     */
    public function __construct()
    {
        $this->middleware = [];
    }

    /**
     * Add middleware to the kernel
     *
     * @param App $app
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function bootstrap(App $app): void
    {
        foreach ($this->middleware as $middleware) {
            $app->container()->get($middleware)->handle();
        }
    }

    public function __destruct()
    {
        unset($this->middleware);
    }
}
