<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Logger\FileLogHandler;
use Bibo\Core\Logger\Logger;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Psr\Container\NotFoundExceptionInterface;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $logger = new Logger([], $_ENV['APP_LOG_LEVEL']);
        $logger->addHandler(
            new FileLogHandler(
                LOG_PATH . $_ENV['LOG_PATH'],
                $_ENV['LOG_FORMAT']
            ),
            $_ENV['APP_LOG_LEVEL']
        );

        $this->container->set('logger', function () use ($logger) {
            return $logger;
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
