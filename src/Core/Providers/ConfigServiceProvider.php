<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Config\Config;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Dotenv\Dotenv;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $dotenv = Dotenv::createImmutable(ROOT_PATH);
        $dotenv->load();
        $config = new Config();
        $config->parseFromEnv();

        $this->container->set('config', function () use ($config) {
            $configs = glob(ROOT_PATH . 'config/*.php');

            foreach ($configs as $file) {
                $config->load($file);
            }

            return $config;
        });
    }

    public function boot(): void
    {
        if ($_ENV['APP_DEBUG'] === true) {
            $logger = $this->container->get('logger');
            $logger->info('Config booted successfully');
        }
    }
}
