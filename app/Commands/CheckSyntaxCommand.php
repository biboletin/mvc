<?php

namespace Bibo\App\Commands;

use Bibo\Mvc\Core\Interfaces\CommandInterface;
use FilesystemIterator;
use Psr\Container\ContainerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CheckSyntaxCommand implements CommandInterface
{
    /**
     * Retrieves the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'check:syntax';
    }

    /**
     * Retrieves the description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Check PHP files in app/ and src/ for syntax errors.';
    }

    /**
     * Executes a specific task using the provided arguments and container.
     *
     * @param array              $args      An array of arguments to be processed during execution.
     * @param ContainerInterface $container The service container used for dependency resolution.
     *
     * @return int The exit code of the command
     */
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

        echo 'Checked ' . $total . " files.\n";

        if ($errors) {
            echo "❌ Syntax errors found:\n";
            foreach ($errors as $file => $msg) {
                echo '- ' . $file . "\n" . $msg . "\n\n";
            }

            unset($errors);

            return 1;
        }

        echo "✅ No syntax errors.\n";
        return 0;
    }
}
