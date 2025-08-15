<?php

namespace Bibo\Mvc\Core\Logger;

use Bibo\Mvc\Core\Interfaces\LogHandlerInterface;

/**
 * FileLogHandler
 * This class is responsible for handling log messages and writing them to a file.
 * It implements the LogHandlerInterface and provides methods for writing log messages.
 * It also handles log rotation based on file size and date.
 */
class FileLogHandler implements LogHandlerInterface
{
    /**
     * Log file path
     *
     * @var string
     */
    protected string $logFile;

    /**
     * Log format
     *
     * @var string
     */
    protected string $format;

    /**
     * Maximum file size for rotation
     *
     * @var int
     */
    protected int $maxFileSize;

    /**
     * Log directory
     *
     * @var string
     */
    protected string $logDir;

    /**
     * FileLogHandler constructor
     *
     * @param string $logDir
     * @param string $format
     * @param int    $maxFileSize
     */
    public function __construct(string $logDir, string $format = 'text', int $maxFileSize = 10485760) // 10MB
    {
        $this->logDir = $logDir;
        $this->format = $format;
        $this->maxFileSize = $maxFileSize;
    }

    /**
     * Write a log message
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function write(string $level, string $message, array $context = []): void
    {
        $this->rotateLogFile(); // Check if file rotation is needed
        $date = date('Y-m-d H:i:s');
        $message = $this->interpolate($message, $context);
        $logEntry = $this->format === 'json'
            ? json_encode(['date' => $date, 'level' => $level, 'message' => $message, 'context' => $context]) . PHP_EOL
            : '[' . $date . '] [' . $level . '] ' . $message . PHP_EOL;

        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }

    /**
     * Interpolate context variables into the message
     *
     * @param string $message
     * @param array  $context
     *
     * @return string
     */
    private function interpolate(string $message, array $context): string
    {
        foreach ($context as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
    }

    /**
     * Rotate the log file if it exceeds the maximum size
     *
     * @return void
     */
    private function rotateLogFile(): void
    {
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }

        $this->logFile = $this->logDir . '/app-' . date('Y-m-d') . '.log'; // Rotate log daily

        if (file_exists($this->logFile) && filesize($this->logFile) > $this->maxFileSize) {
            // Rename for backup
            rename($this->logFile, $this->logDir . '/app-' . date('Y-m-d-H-i-s') . '.log');
        }
    }
}
