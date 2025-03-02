<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Controller\Controller;
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
        $this->container->set('controllers', fn () => new Controller());
    }
}
