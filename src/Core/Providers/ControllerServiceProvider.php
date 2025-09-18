<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Controller\Controller;
use Bibo\Mvc\Core\Logger\Logger;
use Psr\Container\NotFoundExceptionInterface;

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

        $this->container->set(Controller::class, function () use ($controller) {
            return $controller;
        });
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
