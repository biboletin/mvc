<?php

namespace Bibo\Core\Logger;

use Bibo\Core\Interfaces\LogHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
 */
class Logger implements LoggerInterface
{
    /**
     * @var array
     */
    private array $handlersByLevel;
    private string $minLogLevel;
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
     * @param array $handlersByLevel
     */
    public function __construct(array $handlersByLevel = [], string $minLogLevel = LogLevel::ERROR)
    {
        $this->handlersByLevel = $handlersByLevel;
        $this->minLogLevel = $minLogLevel;
    }

    public function setLogLevel(string $level): void
    {
        if (isset(self::LEVELS[$level])) {
            $this->minLogLevel = $level;
        }
    }

    /**
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
