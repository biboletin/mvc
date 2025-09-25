<?php

/**
 * Console commands bootstrapper.
 *
 * This file returns a closure that is executed by the console bootstrap to register
 * all application console commands with the ConsoleKernel.
 *
 * How it works:
 * - Scans the app/Commands directory (APP_PATH . 'Commands') recursively for *.php files.
 * - Derives an FQCN for each file using the base namespace 'Bibo\App\Commands' and the
 *   relative path inside app/Commands. Example:
 *     app/Commands/Maintenance/ClearCache.php -> Bibo\App\Commands\Maintenance\ClearCache
 * - Requires the PHP file if the class is not already loaded.
 * - Registers the command in the kernel only if the class exists and implements
 *   Bibo\Mvc\Core\Interfaces\CommandInterface.
 *
 * Notes:
 * - Only PHP files are considered; non-PHP files are ignored.
 * - If the app/Commands directory does not exist, the function is a no-op.
 * - This keeps command discovery zero-config: just drop a class in app/Commands,
 *   implement CommandInterface, and it will be available to the console.
 */

use Bibo\Mvc\Core\Console\ConsoleKernel;
use Bibo\Mvc\Core\Interfaces\CommandInterface;

return function (ConsoleKernel $kernel): void {

    $commandsDirectory = APP_PATH . 'Commands';

    if (!is_dir($commandsDirectory)) {
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($commandsDirectory, FileSystemIterator::SKIP_DOTS),
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        // Turn filepath into FQCN (App\Commands\...)
        $relativePath = str_replace(
            [$commandsDirectory, '/', '.php'],
            ['', '\\', ''],
            $file->getPathname()
        );
        $class = 'Bibo\\App\\Commands' . $relativePath;

        if (!class_exists($class)) {
            require_once $file->getPathname();
        }

        if (class_exists($class) && in_array(CommandInterface::class, class_implements($class))) {
            $kernel->register(new $class());
        }
    }
};
