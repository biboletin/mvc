<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Application\App;
use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Logger\LogManager;
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
        $config = $this->container->get(ConfigHandler::class);
        $app = new App($this->container);
        $app->setName($config->get('app.name'));

        $this->container->set(App::class, function () use ($app) {
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
        $this->container
            ->get(LogManager::class)
            ->get('app')
            ->debug(__CLASS__ . ' booted successfully');
    }
}
