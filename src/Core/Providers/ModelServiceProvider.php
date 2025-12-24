<?php

namespace Bibo\Mvc\Core\Providers;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
//        $this->container->set('models', fn () => null);
    }

    /**
     * Boot the service provider
     */
    public function boot(): void
    {
    }
}
