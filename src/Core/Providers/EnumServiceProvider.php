<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Enums\Environment;
use Bibo\Mvc\Core\Enums\LogLevels;
use Bibo\Mvc\Core\Facades\Env;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class EnumServiceProvider extends ServiceProvider
{
    /**
     * Register Enums service provider
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
 */
    public function register(): void
    {
        $config = $this->container->get(ConfigHandler::class);

        $this->container->set('log_level', function () use ($config) {
            return LogLevels::fromString($config->get('log.level', 'debug'));
        });
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

        Env::set(
            Environment::fromString($config->get('app.env', 'development'))
        );
    }
}
