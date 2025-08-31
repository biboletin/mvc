<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Base\App;
use Psr\Container\NotFoundExceptionInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        $config = $this->container->get('config');
        $app = new App($this->container);
        $app->setName($config->get('app.name'));

        $this->container->set('app', function () use ($app) {
            return $app;
        });
    }

    /**
     * Boot service
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
