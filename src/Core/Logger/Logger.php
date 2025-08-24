<?php

namespace Bibo\Mvc\Core\Logger;

use Bibo\Mvc\Core\Interfaces\FormatterInterface;
use Bibo\Mvc\Core\Logger\Handler\RotatingFileHandler;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Stringable;

/**
 * Logger class that implements PSR-3 LoggerInterface.
 * This class provides methods for logging messages at various levels
 * and uses a formatter to format the log messages.
 * It supports log rotation through a RotatingFileHandler.
 */
class Logger implements LoggerInterface
{
    /**
     * Formatter instance used to format log messages.
     * This should implement FormatterInterface.
     *
     * @var FormatterInterface
     */
    protected FormatterInterface $formatter;

    /**
     * RotatingFileHandler instance used to handle log writing.
     * This should implement LogHandlerInterface.
     * If null, no handler is set.
     * If a handler is not provided, an exception will be thrown
     * when trying to log messages.
     * If you want to use a handler, you must pass an instance of RotatingFileHandler.
     *
     * @var RotatingFileHandler|null
     */
    protected ?RotatingFileHandler $handler;

    /**
     * Flag to indicate whether to use JSON format for log messages.
     * This is set to false by default.
     * If you want to use JSON format,
     * you must pass an instance of JSONFormatter to the constructor.
     * This flag is not used in this class,
     * but it can be used in subclasses or when extending the functionality.
     *
     * @param FormatterInterface       $formatter
     * @param RotatingFileHandler|null $handler
     */
    public function __construct(FormatterInterface $formatter, RotatingFileHandler $handler = null)
    {
        $this->formatter = $formatter;
        $this->handler = $handler;
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

        $this->handler->write($formattedMessage);
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
