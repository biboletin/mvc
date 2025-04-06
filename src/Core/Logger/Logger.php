<?php

namespace Bibo\Core\Logger;

use Bibo\Core\Interfaces\LogHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Logger
 * This class is responsible for logging messages at different levels.
 * It uses a set of handlers to write log messages to different destinations.
 * It implements the PSR-3 LoggerInterface.
 * It allows for adding custom handlers and setting the minimum log level.
 * It also provides methods for logging messages at different levels.
 * The class is designed to be used within a container, allowing for dependency injection.
 * The log levels are defined as constants for better readability.
 * The class is designed to be extensible, allowing for custom log handlers to be added.
 * It provides a flexible and powerful logging solution for applications.
 */
class Logger implements LoggerInterface
{
    /**
     * Array of handlers indexed by log level
     *
     * @var array
     */
    private array $handlersByLevel;

    /**
     * Minimum log level
     *
     * @var string
     */
    private string $minLogLevel;

    /**
     * Log levels
     *
     * @var array
     */
    private const LEVELS = [
        'debug'     => 100,
        'info'      => 200,
        'notice'    => 250,
        'warning'   => 300,
        'error'     => 400,
        'critical'  => 500,
        'alert'     => 550,
        'emergency' => 600,
    ];

    /**
     * Logger constructor
     *
     * @param array  $handlersByLevel
     * @param string $minLogLevel
     */
    public function __construct(array $handlersByLevel = [], string $minLogLevel = LogLevel::ERROR)
    {
        $this->handlersByLevel = $handlersByLevel;
        $this->minLogLevel = $minLogLevel;
    }

    /**
     * Set the minimum log level
     *
     * @param string $level
     *
     * @return void
     */
    public function setLogLevel(string $level): void
    {
        if (isset(self::LEVELS[$level])) {
            $this->minLogLevel = $level;
        }
    }

    /**
     * Add a log handler for a specific log level
     *
     * @param LogHandlerInterface $handler
     * @param string              $level
     *
     * @return void
     */
    public function addHandler(LogHandlerInterface $handler, string $level): void
    {
        $this->handlersByLevel[$level][] = $handler;
    }

    /**
     * Log a message at a specific log level
     *
     * @param $level
     * @param $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        if (self::LEVELS[$level] < self::LEVELS[$this->minLogLevel]) {
            return;
        }

        foreach ($this->handlersByLevel[$level] ?? [] as $handler) {
            $handler->write($level, $message, $context);
        }
    }

    /**
     * Log a message at the emergency level
     *
     * @param $message
     * @param array $context
     *
     * @return void
     */
    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Alert a message at the alert level
     *
     * @param $message
     * @param array $context
     *
     * @return void
     */
    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Log a message at the critical level
     *
     * @param $message
     * @param array $context
     *
     * @return void
     */
    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Log a message at the error level
     *
     * @param $message
     * @param array $context
     *
     * @return void
     */
    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Log a message at the warning level
     *
     * @param $message
     * @param array $context
     *
     * @return void
     */
    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Log a message at the notice level
     *
     * @param $message
     * @param array $context
     *
     * @return void
     */
    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Log a message at the info level
     *
     * @param $message
     * @param array $context
     *
     * @return void
     */
    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Log a message at the debug level
     *
     * @param $message
     * @param array $context
     *
     * @return void
     */
    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}
