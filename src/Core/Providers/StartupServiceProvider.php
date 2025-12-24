<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Locale;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class StartupServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function register(): void
    {
        $config = $this->container->get(ConfigHandler::class);

        // Any startup code can go here
        date_default_timezone_set($config->get('app.timezone'));

        setlocale(LC_ALL, $config->get('app.locale'));

        if (class_exists(Locale::class)) {
            Locale::setDefault($config->get('app.locale'));
        }
    }

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
