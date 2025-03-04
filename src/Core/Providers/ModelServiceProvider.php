<?php

namespace Bibo\Core\Provider;

use Bibo\Mvc\Core\Providers\ServiceProvider;

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

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
