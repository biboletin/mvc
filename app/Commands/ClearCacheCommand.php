<?php

namespace Bibo\App\Commands;

use Bibo\Mvc\Core\Interfaces\CommandInterface;
use FilesystemIterator;
use Psr\Container\ContainerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClearCacheCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'clear:cache';
    }

    public function getDescription(): string
    {
        return 'Delete all cache files.';
    }

    public function execute(array $args, ContainerInterface $container): int
    {
        if (!is_dir(CACHE_PATH)) {
            echo "No cache directory.\n";
            return 0;
        }

        $count = 0;
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(CACHE_PATH, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($it as $file) {
            if ($file->isFile()) {
                unlink($file->getPathname());
                $count++;
            } elseif ($file->isDir()) {
                rmdir($file->getPathname());
            }
        }

        echo "✅ Cleared $count cached files.\n";
        return 0;
    }
}
