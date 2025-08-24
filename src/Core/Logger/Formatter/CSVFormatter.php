<?php

namespace Bibo\Mvc\Core\Logger\Formatter;

use Bibo\Mvc\Core\Interfaces\FormatterInterface;
use DateTime;
use Stringable;

/**
 * CSVFormatter formats log entries as CSV.
 *
 * It includes a timestamp, log level, message, and optional context.
 * The output is suitable for logging to CSV files.
 */
class CSVFormatter implements FormatterInterface
{
    /**
     * The date format for the timestamp in the log message.
     * This format is used to display the date and time when the log entry was created.
     * It follows the PHP date format conventions.
     * For example, DateTime::ATOM will output the date in the format "2023-10-01T12:34:56+00:00".
     * This property can be customized when creating an instance of the CSVFormatter.
     *
     * @var string
     */
    protected string $dateFormat;

    /**
     * The delimiter used to separate fields in the CSV output.
     * This is typically a comma (`,`), but can be customized to any character.
     * It is used to ensure that the output is correctly formatted as CSV.
     * This property can be set when creating an instance of the CSVFormatter.
     *
     * @var string
     */
    protected string $delimiter;

    /**
     * The enclosure character used for fields in the CSV output.
     * This is typically a double quote (`"`), but can be customized to any character.
     * It is used to ensure that fields containing special characters (like commas or newlines)
     * are correctly enclosed in the output.
     * This property can be set when creating an instance of the CSVFormatter.
     *
     * @var string
     */
    protected string $enclosure;

    /**
     * CSVFormatter constructor.
     * Initializes the formatter with a specific date format, delimiter, and enclosure.
     * This constructor allows you to specify how the log messages should be formatted,
     * including the date format for the timestamp, the delimiter for separating fields,
     * and the enclosure for fields that may contain special characters.
     * This is useful for customizing the output of log messages to suit your needs.
     *
     * @param string $dateFormat The date format to use for timestamps.
     * @param string $delimiter  The delimiter to use for separating fields in the CSV output.
     * @param string $enclosure  The enclosure character to use for fields in the CSV output.
     */
    public function __construct(
        string $dateFormat = DateTime::ATOM,
        string $delimiter = ',',
        string $enclosure = '"'
    ) {
        $this->dateFormat = $dateFormat;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
    }

    /**
     * Formats a log entry into a CSV string.
     * This method takes the log level, message, and optional context,
     * and returns a formatted CSV string that includes
     * the timestamp, level, message, and context (if provided).
     * It is designed to be machine-readable,
     * making it suitable for logging to CSV files.
     *
     * @param string            $level   The log level (e.g., 'info', 'error').
     * @param string|Stringable $message The log message.
     * @param array             $context Optional additional context for the log entry.
     *
     * @return string The formatted CSV string.
     */
    public function format(string $level, string|Stringable $message, array $context = []): string
    {
        $timestamp = date($this->dateFormat);
        $level = strtoupper($level);
        $contextJson = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';

        $fields = [
            $timestamp,
            $level,
            (string) $message,
            $contextJson
        ];

        // Build a CSV line manually to preserve custom delimiter/enclosure
        $escapedFields = array_map(fn($field) => $this->enclosure . str_replace($this->enclosure, $this->enclosure . $this->enclosure, $field) . $this->enclosure, $fields);

        return implode($this->delimiter, $escapedFields);
    }
}
