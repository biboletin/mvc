<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Controller\Controller;

class ControllerServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $controller = new Controller($this->container);

        $this->container->set('controller', function () use ($controller) {
            return $controller;
        });
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
