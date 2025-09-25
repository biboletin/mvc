<?php

namespace Bibo\App\Commands;

use Bibo\Mvc\Core\Interfaces\CommandInterface;
use DirectoryIterator;
use Psr\Container\ContainerInterface;

class ClearLogsCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'clear:logs';
    }

    public function getDescription(): string
    {
        return 'Delete all log files.';
    }

    public function execute(array $args, ContainerInterface $container): int
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            echo "No logs directory.\n";
            return 0;
        }

        $count = 0;
        foreach (new DirectoryIterator($logDir) as $file) {
            if ($file->isFile() && preg_match('/\.log$/', $file->getFilename())) {
                unlink($file->getPathname());
                $count++;
            }
        }

        echo "✅ Deleted $count log file(s).\n";
        return 0;
    }
}
