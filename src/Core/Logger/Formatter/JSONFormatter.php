<?php

namespace Bibo\Mvc\Core\Logger\Formatter;

use Bibo\Mvc\Core\Interfaces\FormatterInterface;
use DateTime;
use Stringable;

/**
 * JSONFormatter formats log entries as JSON.
 * It includes a timestamp, log level, message, and optional context.
 * The output is suitable for machine processing and can be easily parsed.
 * * Example output:
 * ```json
 * {
 *     "timestamp": "2023-10-01T12:34:56+00:00",
 *     "level": "INFO",
 *     "message": "This is a log message",
 *     "context": {
 *        "user_id": 123,
 *        "action": "login"
 *     }
 * }
 * ```
 */
class JSONFormatter implements FormatterInterface
{
    /**
     * The date format for the timestamp in the log message.
     * This format is used to display the date and time when the log entry was created.
     * It follows the PHP date format conventions.
     * For example, \DateTime::ATOM will output the date in the format "2023-10-01T12:34:56+00:00".
     * This property can be customized when creating an instance of the JSONFormatter.
     * The default value is \DateTime::ATOM, which is a standard format for date and time.
     * This property is used to ensure that the timestamp is formatted consistently across all log entries.
     *
     * @var string
     */
    protected string $dateFormat;

    /**
     * Whether to pretty-print the JSON output.
     * If set to true, the JSON output will be formatted with whitespace
     * to make it more human-readable.
     * If set to false, the JSON output will be compact,
     * which is more efficient for storage and transmission.
     * This property can be set when creating an instance of the JSONFormatter.
     * This is useful for debugging and development purposes,
     * as it allows you to easily read and understand the log entries.
     * The default value is false, meaning the JSON will be compact by default.
     * This property is used to control the output format of the JSON log entries,
     * allowing for flexibility based on the use case.
     *
     * @var bool
     */
    protected bool $prettyPrint;

    /**
     * JSONFormatter constructor.
     * Initializes the formatter with a specific date format and pretty-print option.
     * This constructor allows you to specify how the log messages should be formatted,
     * including the date format and whether to pretty-print the JSON output.
     * This is useful for customizing the output of log messages to suit your needs.
     *
     * @param string $dateFormat
     * @param bool   $prettyPrint
     */
    public function __construct(string $dateFormat = DateTime::ATOM, bool $prettyPrint = false)
    {
        $this->dateFormat = $dateFormat;
        $this->prettyPrint = $prettyPrint;
    }

    /**
     * Formats a log entry into a JSON string.
     * This method takes the log level, message, and optional context,
     * and returns a formatted JSON string that includes
     * the timestamp, level, message, and context (if provided).
     * It is designed to be machine-readable,
     * making it suitable for logging to files or sending over a network.
     * The output will look like:
     * ```json
     * {
     *     "timestamp": "2023-10-01T12:34:56+00:00",
     *     "level": "INFO",
     *     "message": "This is a log message",
     *     "context": {
     *         "user_id": 123,
     *         "action": "login"
     *    }
     * }
     * ```
     *
     * @param string            $level
     * @param string|Stringable $message
     * @param array             $context
     *
     * @return string
     */
    public function format(string $level, string|Stringable $message, array $context = []): string
    {
        $record = [
            'timestamp' => date($this->dateFormat),
            'level' => strtoupper($level),
            'message' => (string) $message,
        ];

        if (!empty($context)) {
            $record['context'] = $context;
        }

        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        if ($this->prettyPrint) {
            $flags |= JSON_PRETTY_PRINT;
        }

        return json_encode($record, $flags);
    }
}
