<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Logger\FileLogHandler;
use Bibo\Core\Logger\Logger;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class LogServiceProvider
 *
 * @package Bibo\Core\Provider
 *
 * This service provider is responsible for setting up the logger service.
 */
class LogServiceProvider extends ServiceProvider
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
        $logger = new Logger([], $config->get('app_log_level'));
        $logger->addHandler(
            new FileLogHandler(
                LOG_PATH . $config->get('log_path'),
                $config->get('log_format')
            ),
            $config->get('app_log_level')
        );

        $this->container->set('logger', function () use ($logger) {
            return $logger;
        });
    }

    /**
     * Boot service provider
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
