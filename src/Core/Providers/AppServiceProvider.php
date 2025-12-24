<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Application\App;
use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->container->set(App::class, fn () => new App($this->container));
    }

    /**
     * Boot service provider
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function boot(): void
    {
        $config = $this->container->get(ConfigHandler::class);
        $app = $this->container->get(App::class);

        $app->setName($config->get('app.name'));
        $app->setVersion($config->get('app.version'));
        $app->setDebug($config->get('app.debug'));
    }
}
