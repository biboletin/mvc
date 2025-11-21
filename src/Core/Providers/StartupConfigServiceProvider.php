<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Logger\LogManager;
use Locale;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class StartupConfigServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        try {
            $config = $this->container->get(ConfigHandler::class);

            // Any startup code can go here
            date_default_timezone_set($config->get('app.timezone'));

            setlocale(LC_ALL, $config->get('app.locale'));

            if (class_exists(Locale::class)) {
                Locale::setDefault($config->get('app.locale'));
            }
        } catch (NotFoundExceptionInterface | ReflectionException | ContainerExceptionInterface $e) {
        }
    }

    /**
     * Boot service provider
     *
     * @return void
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
