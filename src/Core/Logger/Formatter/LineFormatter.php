<?php

namespace Bibo\Mvc\Core\Logger\Formatter;

use Bibo\Mvc\Core\Logger\Interfaces\FormatterInterface;
use Stringable;

/**
 * Class LineFormatter
 * This class formats log messages into a single line string.
 * It implements the FormatterInterface and provides a method to format log messages with a
 * timestamp, level, message, and optional context.
 */
class LineFormatter implements FormatterInterface
{
    /**
     * The date format for the timestamp in the log message.
     * This format is used to display the date and time when the log entry was created.
     * It follows the PHP date format conventions.
     * For example, 'Y-m-d H:i:s' will output the date in the format "2023-10-01 12:34:56".
     * This property can be customized when creating an instance of the LineFormatter.
     *
     * @var string
     */
    protected string $dateFormat;
    /**
     * Whether to include context information in the log message.
     * If set to true, any additional context provided when logging will be appended
     * to the log message in JSON format.
     * This is useful for debugging and provides more information about the log entry.
     * If set to false, only the timestamp, level, and message will be included.
     * This property can be set when creating an instance of the LineFormatter.
     *
     * @var bool
     */
    protected bool $includeContext;

    /**
     * LineFormatter constructor.
     * Initializes the formatter with a specific date format and context inclusion option.
     * This constructor allows you to specify how the log messages should be formatted,
     * including the date format and whether to include additional context information.
     * This is useful for customizing the output of log messages to suit your needs.
     *
     * @param string $dateFormat
     * @param bool   $includeContext
     */
    public function __construct(string $dateFormat = 'Y-m-d H:i:s', bool $includeContext = true)
    {
        $this->dateFormat = $dateFormat;
        $this->includeContext = $includeContext;
    }

    /**
     * Formats a log message into a single line string.
     * This method takes the log level, message, and optional context,
     * and returns a formatted string that includes
     * the timestamp, level, message, and context (if included).
     * It is designed to be simple and human-readable,
     * making it suitable for logging to files or console output.
     * The output will look like:
     * ```
     * [2023-10-01 12:34:56] INFO: This is a log message
     * [2023-10-01 12:34:56] ERROR: An error occurred {"error":"Something went wrong"}
     * ```
     * This method is part of the FormatterInterface and must be implemented.
     *
     * @param string            $level
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return string
     */
    public function format(string $level, string|Stringable $message, array $context = []): string
    {
        $timestamp = date($this->dateFormat);
        $level = strtoupper($level);
        $output = sprintf('[%s] %s: %s', $timestamp, $level, (string) $message);

        if ($this->includeContext && !empty($context)) {
            $output .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return $output;
    }
}
