#!/usr/bin/env php
<?php

/**
 * Syntax checker for PHP files in app/ and src/ directories.
 *
 * Usage: php mvc/scripts/check-syntax.php
 */

$directories = [
    __DIR__ . '/../app',
    __DIR__ . '/../src',
];

$errors = [];
$totalFiles = 0;

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        fwrite(STDERR, "Warning: Directory not found: $dir\n");
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $totalFiles++;
        $filePath = $file->getPathname();

        $output = [];
        $returnVar = 0;
        exec("php -l " . escapeshellarg($filePath) . " 2>&1", $output, $returnVar);

        if ($returnVar !== 0) {
            $errors[$filePath] = implode("\n", $output);
        }
    }
}

echo "Checked $totalFiles PHP files.\n";

if ($errors) {
    echo "Syntax errors found in " . count($errors) . " file(s):\n\n";
    foreach ($errors as $file => $message) {
        echo "File: $file\n";
        echo $message . "\n\n";
    }
    exit(1);
} else {
    echo "✅ No syntax errors detected.\n";
    exit(0);
}
