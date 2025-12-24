<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Response\ResponseEmitter;

class ResponseEmitterServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->container->set(ResponseEmitter::class, fn () => new ResponseEmitter());
    }

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
