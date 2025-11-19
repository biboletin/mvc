#!/usr/bin/env php
<?php

/**
 * Clear all log files in the logs directory.
 *
 * Usage: php mvc/scripts/clear_logs.php
 */

$logDir = __DIR__ . '/../logs'; // adjust if your logs are elsewhere

if (!is_dir($logDir)) {
    fwrite(STDERR, "Log directory not found: $logDir\n");
    exit(1);
}

$deleted = 0;
$cleared = 0;

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($logDir, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST
);

foreach ($iterator as $file) {
    if ($file->isFile() && preg_match('/\.log$/', $file->getFilename())) {
        $path = $file->getPathname();

        // Option 1: Clear file contents (keep file)
        if (file_put_contents($path, '') !== false) {
            $cleared++;
        }

        // Option 2: Or delete the file completely
        // if (unlink($path)) {
        //     $deleted++;
        // }
    }
}

echo "Logs cleared successfully.\n";
echo "Cleared: $cleared file(s).\n";
echo "Deleted: $deleted file(s).\n";

exit(0);
