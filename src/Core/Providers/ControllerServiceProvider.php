<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Controller\Controller;
use Bibo\Mvc\Core\Providers\ServiceProvider;

class ControllerServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $container = $this->container;

        $container->set('controller', function () use ($container) {
            new Controller($container);
        });
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
