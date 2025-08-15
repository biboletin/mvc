<?php

namespace Bibo\Mvc\Core\Interfaces;

use Stringable;

/**
 * FormatterInterface defines the contract for log formatters.
 * It requires a method to format log entries into a string representation.
 * Implementations of this interface should handle the formatting of log messages,
 * including the log level, message, and any additional context.
 * Example implementations could include formatting for plain text, JSON, HTML, CSV, etc.
 */
interface FormatterInterface
{
    /**
     * Formats a log entry into a string representation.
     * This method takes the log level, message, and optional context,
     * and returns a formatted string.
     * The output format can vary depending on the implementation,
     * but it should always include the log level and message.
     * For example, a simple text formatter might return:
     * "2023-10-01 12:34:56 [INFO] This is a log message"
     * or a JSON formatter might return:
     * {
     *   "timestamp": "2023-10-01T12:34:56Z",
     *   "level": "INFO",
     *   "message": "This is a log message"
     *   "context": {}
     *  }
     * * Implementations should ensure that the output is suitable for the intended use case,
     * * whether that be for human readability
     * * or for machine processing.
     *
     * @param string            $level
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return string
     */
    public function format(string $level, string|Stringable $message, array $context = []): string;
}
