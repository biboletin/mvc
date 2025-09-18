<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Logger\Formatter\LineFormatter;
use Bibo\Mvc\Core\Logger\Handler\RotatingFileHandler;
use Bibo\Mvc\Core\Logger\Logger;
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
        $config = $this->container->get(ConfigHandler::class);

        $formatter = new LineFormatter(
            $config->get('log.date_format'),
            $config->get('log.include_context')
        );

        $rotatingLogHandler = new RotatingFileHandler(
            LOG_PATH . 'app',
            'error.log',
            $config->get('log.max_files', 5)
        );

        $logger = new Logger($formatter);
        $logger->setLogLevel($config->get('log.level'));
        $logger->addHandler($rotatingLogHandler, $config->get('log.level'));

        $this->container->set(Logger::class, function () use ($logger) {
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
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
