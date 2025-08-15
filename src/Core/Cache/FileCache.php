<?php

namespace Bibo\Mvc\Core\Cache;

use DateInterval;
use DateTime;
use Psr\SimpleCache\CacheInterface;

/**
 * Simple file caching class
 * implements PSR-16
 */
class FileCache implements CacheInterface
{
    /**
     * Cache directory
     *
     * @var string
     */
    protected string $cacheDir;

    /**
     * Constructor
     *
     * @param string|null $cacheDir
     */
    public function __construct(?string $cacheDir = '')
    {
        // Ensure cache directory exists
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        $this->cacheDir = rtrim($cacheDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * Get item
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $file = $this->getCacheFilePath($key);

        if (!file_exists($file)) {
            return null;
        }

        $compressedData = file_get_contents($file);

        // Decompress the data
        $data = gzuncompress($compressedData);

        // Return null if the decompression failed
        if ($data === false) {
            return null;
        }

        return unserialize($data); // Assuming the cache data is serialized
    }

    /**
     * Store a cache item.
     *
     * @param string                $key   Cache key
     * @param mixed                 $value Cache value
     * @param int|DateInterval|null $ttl   Cache TTL (optional)
     *
     * @return bool True on success, false on failure
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $data = serialize($value); // Serialize data before caching
        // Compress the serialized data
        $compressedData = gzcompress($data);

        return file_put_contents($this->getCacheFilePath($key), $compressedData) !== false;
    }


    /**
     * Delete a cache item.
     *
     * @param string $key Cache key
     *
     * @return bool True on success, false on failure
     */
    public function delete(string $key): bool
    {
        $file = $this->getCacheFilePath($key);

        if (!file_exists($file)) {
            return false;
        }

        return unlink($file);
    }

    /**
     * Clears all cache
     *
     * @return bool
     */
    public function clear(): bool
    {
        // Delete all cache files
        $files = glob($this->cacheDir . '*.cache');
        foreach ($files as $file) {
            unlink($file);
        }

        return true;
    }

    /**
     * Check if a cache item exists.
     *
     * @param string $key Cache key
     *
     * @return bool True if the cache item exists, false otherwise
     */
    public function has(string $key): bool
    {
        return file_exists($this->getCacheFilePath($key));
    }

    /**
     * Get the file path for a given cache key.
     *
     * @param string $key Cache key
     *
     * @return string Cache file path
     */
    protected function getCacheFilePath(string $key): string
    {
        return $this->cacheDir . md5($key) . '.cache';
    }

    /**
     * Get multiple cache items.
     *
     * @param iterable<string> $keys    List of keys
     * @param mixed            $default Default value for missing keys
     *
     * @return iterable<string, mixed> Key-value pairs of cached data
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $results = [];

        foreach ($keys as $key) {
            $results[$key] = $this->get($key) ?? $default;
        }

        return $results;
    }

    /**
     * Store multiple cache items.
     *
     * @param iterable              $values Key-value pairs to store
     * @param int|DateInterval|null $ttl    Cache TTL (optional)
     *
     * @return bool True on success, false on failure
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        $success = true;

        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Convert a DateInterval to seconds.
     *
     * @param DateInterval $interval
     *
     * @return int The number of seconds represented by the DateInterval.
     */
    protected function convertDateIntervalToSeconds(DateInterval $interval): int
    {
        $dateTime = new DateTime();
        $dateTime->add($interval);
        return $dateTime->getTimestamp() - time();
    }

    /**
     * Delete multiple cache items.
     *
     * @param iterable<string> $keys List of keys
     *
     * @return bool True on success, false on failure
     */
    public function deleteMultiple(iterable $keys): bool
    {
        $success = true;

        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                $success = false;
            }
        }

        return $success;
    }

    public function setPath(string $path): void
    {
        $this->cacheDir .= $path;
    }

    public function getPath(): string
    {
        return $this->cacheDir;
    }
}
