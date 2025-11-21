<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Application\App;
use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Enums\AppVersion;
use Bibo\Mvc\Core\Logger\LogManager;
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
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        try {
            $config = $this->container->get(ConfigHandler::class);
            $app = new App($this->container);
            $app->setName($config->get('app.name'));
            $app->setVersion($config->get('app.version'));

            $this->container->set(App::class, function () use ($app) {
                return $app;
            });
        } catch (NotFoundExceptionInterface | ReflectionException | ContainerExceptionInterface $e) {
        }
    }

    /**
     * Boot service
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        try {
            $this->container
                ->get(LogManager::class)
                ->get('app')
                ->debug(__CLASS__ . ' booted successfully');
        } catch (NotFoundExceptionInterface | ReflectionException | ContainerExceptionInterface $e) {
        }
    }
}
