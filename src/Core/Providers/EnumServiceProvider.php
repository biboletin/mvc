<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Enums\Environment;
use Bibo\Mvc\Core\Enums\LogLevel;
use Bibo\Mvc\Core\Facades\Env;
use Psr\Container\NotFoundExceptionInterface;

class EnumServiceProvider extends ServiceProvider
{
    /**
     * Register Enums service provider
     *
     * @throws NotFoundExceptionInterface
*/
    public function register(): void
    {
        $config = $this->container->get('config');

        Env::set(
            Environment::fromString($config->get('app.env', 'development'))
        );

        $this->container->set('log_level', function () use ($config) {
            return LogLevel::fromString($config->get('log.level', 'debug'));
        });
    }

    /**
     * Boot Enums service provider
     *
     * @return void
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
