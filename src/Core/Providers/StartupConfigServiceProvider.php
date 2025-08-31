<?php

namespace Bibo\Mvc\Core\Providers;

use Locale;
use Psr\Container\NotFoundExceptionInterface;

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
        $config = $this->container->get('config');

        // Any startup code can go here
        date_default_timezone_set($config->get('app.timezone'));

        setlocale(LC_ALL, $config->get('app.locale'));

        if (class_exists(Locale::class)) {
            Locale::setDefault($config->get('app.locale'));
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
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
