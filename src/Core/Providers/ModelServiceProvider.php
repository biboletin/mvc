<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Logger\Logger;
use Psr\Container\NotFoundExceptionInterface;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->set('models', fn () => null);
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
