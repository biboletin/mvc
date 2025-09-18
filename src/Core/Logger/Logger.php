<?php

namespace Bibo\Mvc\Core\Logger;

use Bibo\Mvc\Core\Enums\LogLevels;
use Bibo\Mvc\Core\Interfaces\FormatterInterface;
use Bibo\Mvc\Core\Interfaces\LogHandlerInterface;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Stringable;

// use http\Exception\InvalidArgumentException;

/**
 * Logger class that implements PSR-3 LoggerInterface.
 * This class provides methods for logging messages at various levels
 * and uses a formatter to format the log messages.
 * It supports log rotation through a RotatingFileHandler.
 */
class Logger implements LoggerInterface
{
    /**
     * Log handlers
     *
     * @var array
     */
    private array $handlersByLevel = [];

    /**
     * Log level
     *
     * @var int
     */
    private int $minLogLevel = 500;

    /**
     * Log levels
     *
     * @var int[]
     */
    private const LEVELS = [
        LogLevels::Debug->value => 100,
        LogLevels::Info->value => 200,
        LogLevels::Notice->value => 300,
        LogLevels::Warning->value => 400,
        LogLevels::Error->value => 500,
        LogLevels::Critical->value => 600,
        LogLevels::Alert->value => 700,
        LogLevels::Emergency->value => 800,
    ];
    /**
     * Formatter instance used to format log messages.
     * This should implement FormatterInterface.
     *
     * @var FormatterInterface
     */
    protected FormatterInterface $formatter;

    /**
     * Flag to indicate whether to use JSON format for log messages.
     * This is set to false by default.
     * If you want to use JSON format,
     * you must pass an instance of JSONFormatter to the constructor.
     * This flag is not used in this class,
     * but it can be used in subclasses or when extending the functionality.
     *
     * @param FormatterInterface $formatter
     */
    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Set log level
     *
     * @param string $level
     *
     * @return void
     */
    public function setLogLevel(string $level): void
    {
        if (isset(self::LEVELS[strtolower($level)])) {
            $this->minLogLevel = self::LEVELS[strtolower($level)];
        }
    }

    /**
     * Add log handler
     *
     * @param LogHandlerInterface $handler
     * @param string|array        $levels
     *
     * @return void
     */
    public function addHandler(LogHandlerInterface $handler, string|array $levels): void
    {
        $levels = (array) $levels;

        foreach ($levels as $level) {
            $level = strtolower($level);

            if (!isset(self::LEVELS[$level])) {
                throw new InvalidArgumentException('Invalid log level: ' . $level);
            }
            $this->handlersByLevel[$level][] = $handler;
        }
    }

    /**
     * Logs a message at the emergency level.
     * This method is used for critical errors that require immediate attention.
     * It will log the message using the configured handler
     * and format it using the formatter.
     *
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Logs a message at the alert level.
     * This method is used for urgent issues that need immediate attention.
     * It will log the message using the configured handler
     * and format it using the formatter.
     *
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Logs a message at the critical level.
     * This method is used for critical errors that may cause the application to stop.
     * It will log the message using the configured handler
     * and format it using the formatter.
     *
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Logs a message at the error level.
     * This method is used for errors that do not require immediate attention
     * but should be investigated.
     * It will log the message using the configured handler
     * and format it using the formatter.
     *
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function error(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Logs a message at the warning level.
     * This method is used for warnings that may indicate a potential problem
     * but do not require immediate action.
     * It will log the message using the configured handler
     * and format it using the formatter.
     *
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Logs a message at the notice level.
     * This method is used for normal but significant events
     * that should be logged.
     * It will log the message using the configured handler
     * and format it using the formatter.
     *
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Logs a message at the info level.
     * This method is used for informational messages
     * that do not indicate an error or warning.
     * It will log the message using the configured handler
     * and format it using the formatter.
     *
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Logs a message at the debug level.
     * This method is used for debugging messages
     * that provide detailed information
     * about the application's state.
     * It will log the message using the configured handler
     * and format it using the formatter.
     * This level is typically used during development
     * and may not be enabled in production environments.
     *
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Logs a message at the specified level.
     * This method is the core logging method
     * that handles the actual logging process.
     * It formats the message using the provided formatter
     * and writes it to the configured handler.
     * If no handler is set, an exception will be thrown.
     *
     * @param $level
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return void
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $interpolated = $this->interpolate($message, $context);
        $formattedMessage = $this->formatter->format($level, $interpolated, $context);

        if (self::LEVELS[strtolower($level)] < $this->minLogLevel) {
            return;
        }

        foreach ($this->handlersByLevel[strtolower($level)] ?? [] as $handler) {
            $handler->write($formattedMessage);
        }
    }

    /**
     * Interpolates the message with the provided context.
     * This method replaces placeholders in the message
     * with values from the context array.
     * Placeholders are in the format {{key}},
     * where key is a key in the context array.
     * If a key in the context array is not found in the message, it will be ignored.
     * This method is used to prepare the message for logging
     * and to ensure that all context values
     * are included in the final log message.
     * It is a private method and should not be called directly outside of this class.
     *
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return string
     */
    private function interpolate(string|Stringable $message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $value) {
            $replace['{{' . $key . '}}'] = $value;
        }

        return strtr($message, $replace);
    }
}
