<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Logger\Logger;
use Bibo\Mvc\Core\Response\ResponseEmitter;
use Psr\Container\NotFoundExceptionInterface;

class ResponseEmitterServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->set(ResponseEmitter::class, fn () => new ResponseEmitter());
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
