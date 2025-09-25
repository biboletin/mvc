<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Enums\Environment;
use Bibo\Mvc\Core\Enums\LogLevels;
use Bibo\Mvc\Core\Facades\Env;
use Bibo\Mvc\Core\Logger\LogManager;
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
        $config = $this->container->get(ConfigHandler::class);

        Env::set(
            Environment::fromString($config->get('app.env', 'development'))
        );

        $this->container->set('log_level', function () use ($config) {
            return LogLevels::fromString($config->get('log.level', 'debug'));
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
        $this->container
            ->get(LogManager::class)
            ->get('app')
            ->debug(__CLASS__ . ' booted successfully');
    }
}
