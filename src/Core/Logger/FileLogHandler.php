<?php

namespace Bibo\Core\Logger;

use Bibo\Core\Interfaces\LogHandlerInterface;
use DateTime;

class FileLogHandler implements LogHandlerInterface
{
    protected string $logFile;
    protected string $format;
    protected int $maxFileSize;
    protected string $logDir;

    public function __construct(string $logDir, string $format = 'text', int $maxFileSize = 10485760) // 10MB
    {
        $this->logDir = $logDir;
        $this->format = $format;
        $this->maxFileSize = $maxFileSize;
    }

    public function write(string $level, string $message, array $context = []): void
    {
        $this->rotateLogFile(); // Check if file rotation is needed
        $date = date('Y-m-d H:i:s');
        $message = $this->interpolate($message, $context);
        $logEntry = $this->format === 'json'
            ? json_encode(['date' => $date, 'level' => $level, 'message' => $message, 'context' => $context]) . PHP_EOL
            : "[$date] [$level] $message" . PHP_EOL;

        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }

    private function interpolate(string $message, array $context): string
    {
        foreach ($context as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
    }

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
