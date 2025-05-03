<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Config\Config;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Psr\Container\NotFoundExceptionInterface;

class ConfigServiceProvider extends ServiceProvider
{
    private const string CACHE_FILE = CONFIG_CACHE_PATH . 'config.php';

    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        Config::load(ROOT_PATH . '.env');

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
