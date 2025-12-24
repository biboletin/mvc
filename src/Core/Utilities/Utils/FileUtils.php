<?php

namespace Bibo\Mvc\Core\Utilities\Utils;

trait FileUtils
{
    public static function getMimeType($file): string
    {
    }

    public static function getExtension($file): string
    {
    }

    public static function getSize($file): int
    {
    }

    public static function getFileName($file): string
    {
    }

    public static function getFileNameWithoutExtension($file): string
    {
    }

    public static function getFileNameWithExtension($file): string
    {
    }

    public static function getBaseName($file): string
    {
    }

    public static function getRealPath($file): string
    {
    }

    public static function getAbsolutePath($file): string
    {
    }

    public static function getRelativePath($file): string
    {
    }

    public static function getDirectory($file): string
    {
    }

    public static function getFiles($directory): array
    {
    }

    public static function getDirectories($directory): array
    {
    }

    public static function getFilesAndDirectories($directory): array
    {
    }

    public static function fileExists(string $path): bool
    {
    }

    public static function isReadable(string $path): bool
    {
    }

    public static function isWritable(string $path): bool
    {
    }

    public static function isExecutable(string $path): bool
    {
    }

    public static function copy(string $source, string $destination): bool
    {
    }

    public static function move(string $source, string $destination): bool
    {
    }

    public static function delete(string $path): bool
    {
    }

    public static function createDirectory(string $path, int $mode = 0777, bool $recursive = false): bool
    {
    }

    public static function createFile(string $path, int $mode = 0666): bool
    {
    }

    public static function getMimeTypeByExtension(string $extension): string
    {
    }

    public static function getExtensionByMimeType(string $mimeType): string
    {
    }

    public static function minimize(string $file): bool
    {
    }

    public static function compress(string $file): bool
    {
    }

    public static function decompress(string $file): bool
    {
    }

    public static function encrypt(string $file): bool
    {
    }

    public static function decrypt(string $file): bool
    {
    }

    public static function hash(string $file): bool
    {
    }

    public static function hashFile(string $file, string $algo): bool
    {
    }
}
