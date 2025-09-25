<?php

namespace Bibo\App\Commands;

use Bibo\Mvc\Core\Interfaces\CommandInterface;
use FilesystemIterator;
use Psr\Container\ContainerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CheckSyntaxCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'check:syntax';
    }

    public function getDescription(): string
    {
        return 'Check PHP files in app/ and src/ for syntax errors.';
    }

    public function execute(array $args, ContainerInterface $container): int
    {
        $dirs = [
            ROOT_PATH . 'app',
            ROOT_PATH . 'bootstrap',
            ROOT_PATH . 'config',
            ROOT_PATH . 'routes',
            ROOT_PATH . 'src',
        ];
        $errors = [];
        $total = 0;

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }
                $total++;
                exec(
                    'php -l ' . escapeshellarg($file->getPathname()),
                    $out,
                    $ret
                );

                if ($ret !== 0) {
                    $errors[$file->getPathname()] = implode("\n", $out);
                }
            }
        }

        unset($dirs);
        unset($iterator);

        echo "Checked $total files.\n";

        if ($errors) {
            echo "❌ Syntax errors found:\n";
            foreach ($errors as $file => $msg) {
                echo "- $file\n$msg\n\n";
            }

            unset($errors);

            return 1;
        }

        echo "✅ No syntax errors.\n";
        return 0;
    }
}
