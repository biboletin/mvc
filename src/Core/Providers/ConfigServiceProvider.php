<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Config\Config;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws Exception
     */
    public function register(): void
    {
        // Config::load();
        Config::loadFromFile(ROOT_PATH . '.env');
        $config = Config::all();
        $this->container->set('config', function () use ($config) {
            return $config;
        });
    }


    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
