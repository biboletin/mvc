<?php

namespace Bibo\App\Commands;

use Bibo\Mvc\Core\Interfaces\CommandInterface;
use Psr\Container\ContainerInterface;

class HealthCheckCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'system:health';
    }

    public function getDescription(): string
    {
        return 'Run a system health check (PHP version, extensions, dirs).';
    }

    public function execute(array $args, ContainerInterface $container): int
    {
        echo 'PHP version: ' . PHP_VERSION . "\n";

        $required = [
            'pdo',
            'mbstring',
            'json',
            'zlib',
            'ftp',
            'curl',
            'dom',
            'libxml',
            'openssl',
            'bz2',
            'intl',
            'bcmath',
        ];

        foreach ($required as $ext) {
            echo sprintf(
                "- %-10s %s\n",
                $ext,
                extension_loaded($ext) ? '✅' : '❌ missing'
            );
        }

        $dirs = [
            'logs',
            'cache',
            'backups',
            'session',
            'tmp',
        ];

        foreach ($dirs as $dir) {
            $path = ROOT_PATH . "/storage/$dir";
            echo sprintf(
                "- %-10s %s\n",
                $dir . '/',
                is_writable($path) ? '✅ writable' : '❌ not writable'
            );
        }

        echo "✅ Health check completed.\n";
        return 0;
    }
}
