<?php

namespace Bibo\Core\Base;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Kernel
{
    protected array $middleware = [];

    public function __construct()
    {
        $this->middleware = [];
    }

    /**
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
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