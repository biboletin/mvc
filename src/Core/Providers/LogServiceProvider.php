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
        $logger = new Logger();
        $logger->addHandler(new FileLogHandler(LOG_PATH . $_ENV['LOG_PATH']), 'info');

        $this->container->set('logger', function () use ($logger) {
            return $logger;
        });
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        if ($_ENV['APP_DEBUG'] === true) {
            $logger = $this->container->get('logger');
            $logger->info('Logger booted successfully');
        }
    }
}
