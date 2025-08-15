<?php

namespace Bibo\Mvc\Core\Logger\Formatter;

use Bibo\Mvc\Core\Interfaces\FormatterInterface;

/**
 * HtmlFormatter formats log entries as HTML.
 *
 * It includes a timestamp, log level, message, and optional context.
 * The output is suitable for rendering in a web browser.
 *
 * .log-entry { margin-bottom: 1em; font-family: monospace; padding: 0.5em; border: 1px solid #ccc; }
 * .log-info { background-color: #e7f4e4; }
 * .log-error { background-color: #fbeaea; }
 * .log-warning { background-color: #fff6d1; }
 * .log-debug { background-color: #f0f0f0; }
 * .log-context { font-size: 0.9em; color: #555; }
 */
class HtmlFormatter implements FormatterInterface
{
    /**
     * The date format for the timestamp in the log message.
     * This format is used to display the date and time when the log entry was created.
     * It follows the PHP date format conventions.
     * For example, \DateTime::ATOM will output the date in the format "2023-10-01T12:34:56+00:00".
     * This property can be customized when creating an instance of the HtmlFormatter.
     *
     * @var string
     */
    protected string $dateFormat;

    /**
     * HtmlFormatter constructor.
     * Initializes the formatter with a specific date format.
     * This constructor allows you to specify how the log messages should be formatted,
     * including the date format for the timestamp.
     * This is useful for customizing the output of log messages to suit your needs.
     *
     * @param string $dateFormat The date format to use for timestamps.
     */
    public function __construct(string $dateFormat = \DateTime::ATOM)
    {
        $this->dateFormat = $dateFormat;
    }

    public function format(string $level, string|\Stringable $message, array $context = []): string
    {
        $timestamp = date($this->dateFormat);
        $level = strtoupper($level);
        $message = htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8');
        $contextJson = !empty($context)
            ? '<pre class="log-context">' . htmlspecialchars(
                json_encode($context, JSON_PRETTY_PRINT),
                ENT_QUOTES,
                'UTF-8'
            ) . '</pre>'
            : '';

        return <<<HTML
<div class="log-entry log-{$level}">
    <span class="log-timestamp">{$timestamp}</span>
    <span class="log-level">{$level}</span>
    <span class="log-message">{$message}</span>
    {$contextJson}
</div>
HTML;
    }
}
