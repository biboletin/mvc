<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Interfaces\ConfigInterface;
use Bibo\Mvc\Core\Interfaces\FormatterInterface;
use Bibo\Mvc\Core\Logger\Formatter\JSONFormatter;
use Bibo\Mvc\Core\Logger\Formatter\LineFormatter;
use Bibo\Mvc\Core\Logger\Handler\RotatingFileHandler;
use Bibo\Mvc\Core\Logger\Logger;
use Bibo\Mvc\Core\Logger\LogManager;
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

        $loggers = [
            'app' => $this->createLogger(
                $config,
                'app',
                'error.log',
                new LineFormatter(
                    $config->get('log.date_format'),
                    $config->get('log.include_context')
                )
            ),

            'security' => $this->createLogger(
                $config,
                'security',
                'error.json',
                new JsonFormatter(
                    $config->get('log.date_format'),
                    $config->get('log.channels.security.pretty_print')
                )
            ),
        ];

        $this->container->set(LogManager::class, function () use ($loggers) {
            return new LogManager($loggers);
        });
    }

    /**
     * Create logger instance
     * so can it log different logs
     *
     * @param ConfigInterface    $config
     * @param string             $channel
     * @param string             $filename
     * @param FormatterInterface $formatter
     *
     * @return Logger
     */
    private function createLogger(
        ConfigInterface $config,
        string $channel,
        string $filename,
        FormatterInterface $formatter
    ): Logger {
        $rotatingLogHandler = new RotatingFileHandler(
            LOG_PATH . $channel,
            $filename,
            $config->get('log.max_files', 5)
        );

        $logger = new Logger($formatter);
        $logger->setLogLevel($config->get('log.level'));
        $logger->addHandler($rotatingLogHandler, $config->get('log.level'));

        return $logger;
    }

    /**
     * Boot service provider
     *
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
