<?php

namespace Bibo\Mvc\Core\Logger\Handler;

/**
 * RotatingFileHandler handles log writing to files with rotation.
 * It creates a new log file for each day and keeps a limited number of files.
 * When the maximum number of files is reached, the oldest files are deleted.
 * This is useful for managing log file sizes and keeping logs organized by date.
 */
class RotatingFileHandler
{
    /**
     * The directory where log files will be stored.
     * This should be a writable directory on the filesystem.
     * If the directory does not exist, it will be created with appropriate permissions.
     *
     * @var string
     */
    protected string $directory;

    /**
     * The base filename for the log files.
     * Each log file will be named with the current date and this base filename.
     * For example, if the base filename is 'app.log', the log file for today will be '2023-10-01-app.log'.
     *
     * @var string
     */
    protected string $filename;

    /**
     * The maximum number of log files to keep.
     * When the limit is reached, the oldest log files will be deleted.
     * This helps to manage disk space and prevents excessive log file accumulation.
     *
     * @var int
     */
    protected int $maxFiles;

    /**
     * RotatingFileHandler constructor.
     * Initializes the handler with a directory, filename, and maximum number of files.
     * It ensures that the directory exists and prepares for log rotation.
     *
     * @param string $directory The directory where log files will be stored.
     * @param string $filename  The base filename for the log files (default is 'app.log').
     * @param int    $maxFiles  The maximum number of log files to keep (default is 5).
     */
    public function __construct(string $directory, string $filename = 'app.log', int $maxFiles = 5)
    {
        $this->directory = rtrim($directory, '/');
        $this->filename = $filename;
        $this->maxFiles = $maxFiles;

        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        }

        $this->rotateLogs();
    }

    /**
     * Writes a log message to the appropriate log file.
     * The log file is determined by the current date, and the message is appended to it.
     * If the log file for today does not exist, it will be created.
     *
     * @param string $message The log message to write.
     */
    public function write(string $message): void
    {
        $date = date('Y-m-d');
        $logFile = $this->directory . '/' . $date . '-' . $this->filename;

        file_put_contents($logFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    /**
     * Rotates the log files by deleting the oldest files if the maximum number of files is exceeded.
     * It keeps the most recent log files based on their modification time.
     * This method is called automatically when the handler is initialized.
     */
    protected function rotateLogs(): void
    {
        $files = glob($this->directory . '/*-' . $this->filename);

        usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));

        if (count($files) >= $this->maxFiles) {
            foreach (array_slice($files, $this->maxFiles) as $file) {
                unlink($file);
            }
        }
    }
}
