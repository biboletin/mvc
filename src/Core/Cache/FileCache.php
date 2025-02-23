<?php

namespace Bibo\Core\Cache;

use DateInterval;
use DateTime;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;
use Traversable;
use InvalidArgumentException;

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
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    public function get($key, $default = null): mixed
    {
        $file = $this->getCacheFilePath($key);

        // If the cache file doesn't exist, return the default value
        if (!file_exists($file)) {
            return $default;
        }

        // Lock file for reading
        $fileHandle = fopen($file, 'r');
        if ($fileHandle === false) {
            throw new RuntimeException("Unable to open cache file for reading: $file");
        }

        // Apply shared read lock
        if (flock($fileHandle, LOCK_SH)) {
            $data = unserialize(fread($fileHandle, filesize($file)));

            // Release the lock
            flock($fileHandle, LOCK_UN);
            fclose($fileHandle);

            // Check expiration
            if ($data['expires_at'] < time()) {
                unlink($file); // Remove expired file
                return $default;
            }

            return $data['value'];
        } else {
            fclose($fileHandle);
            throw new RuntimeException("Unable to lock cache file for reading: $file");
        }
    }

    /**
     * Set item
     *
     * @param $key
     * @param $value
     * @param $ttl
     *
     * @return bool
     */
    public function set($key, $value, $ttl = null): bool
    {
        $file = $this->getCacheFilePath($key);

        // Prepare the cache data
        $data = [
            'value' => $value,
            'expires_at' => time() + ($ttl ?? 3600) // Default expiration is 1 hour
        ];

        // Lock file for writing
        $fileHandle = fopen($file, 'c+');
        if ($fileHandle === false) {
            throw new RuntimeException("Unable to open cache file for writing: $file");
        }

        // Apply exclusive write lock
        if (flock($fileHandle, LOCK_EX)) {
            fwrite($fileHandle, serialize($data));  // Write the data
            fflush($fileHandle);  // Ensure data is written to disk
            flock($fileHandle, LOCK_UN);  // Release the lock
        } else {
            fclose($fileHandle);
            throw new RuntimeException("Unable to lock cache file for writing: $file");
        }

        fclose($fileHandle);
        return true;
    }

    /**
     * Delete an item
     *
     * @param $key
     *
     * @return bool
     */
    public function delete($key): bool
    {
        $file = $this->getCacheFilePath($key);

        // Remove cache file if it exists
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
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
     * Check if key exists
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        $file = $this->getCacheFilePath($key);

        if (!file_exists($file)) {
            return false;
        }

        // Lock file for reading
        $fileHandle = fopen($file, 'r');
        if ($fileHandle === false) {
            throw new RuntimeException("Unable to open cache file for reading: $file");
        }

        // Apply shared read lock
        if (flock($fileHandle, LOCK_SH)) {
            $data = unserialize(fread($fileHandle, filesize($file)));

            // Release the lock
            flock($fileHandle, LOCK_UN);
            fclose($fileHandle);

            return $data['expires_at'] >= time();
        } else {
            fclose($fileHandle);
            throw new RuntimeException("Unable to lock cache file for reading: $file");
        }
    }

    /**
     * Get cache file
     *
     * @param $key
     *
     * @return string
     */
    protected function getCacheFilePath($key): string
    {
        // Hash the key and store the cache data in a file with a unique name
        return $this->cacheDir . md5($key) . '.cache';
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable<string> $keys    A list of keys that can be obtained in a single operation.
     * @param mixed            $default Default value to return for keys that do not exist.
     *
     * @return iterable<string, mixed> A list of key => value pairs. FileCache keys that do not exist or are stale will have $default as value.
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        // Ensure that $keys is an array or Traversable
        if (!is_array($keys) && !$keys instanceof Traversable) {
            throw new InvalidArgumentException('The ' . $keys . ' parameter must be an array or Traversable.');
        }

        $result = [];

        foreach ($keys as $key) {
            // Fetch each cache item
            $file = $this->getCacheFilePath($key);

            if (!file_exists($file)) {
                // If the file doesn't exist, return default value
                $result[$key] = $default;
                continue;
            }

            // Lock file for reading
            $fileHandle = fopen($file, 'r');
            if ($fileHandle === false) {
                throw new RuntimeException("Unable to open cache file for reading: $file");
            }

            // Apply shared lock
            if (flock($fileHandle, LOCK_SH)) {
                $data = unserialize(fread($fileHandle, filesize($file)));

                // Release the lock
                flock($fileHandle, LOCK_UN);
                fclose($fileHandle);

                // Check expiration
                if ($data['expires_at'] < time()) {
                    // If expired, remove the file and return the default value
                    unlink($file);
                    $result[$key] = $default;
                } else {
                    $result[$key] = $data['value'];
                }
            } else {
                fclose($fileHandle);
                throw new RuntimeException("Unable to lock cache file for reading: $file");
            }
        }

        return $result;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable              $values  A list of key => value pairs for a multiple-set operation.
     * @param null|int|DateInterval $ttl     Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if $values is neither an array nor a Traversable,
     *   or if any of the $values are not a legal value.
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        // Ensure that $values is an array or Traversable
        if (!is_array($values) && !$values instanceof \Traversable) {
            throw new InvalidArgumentException('The ' . $values . ' parameter must be an array or Traversable.');
        }

        // If TTL is provided as \DateInterval, convert it to seconds
        if ($ttl instanceof DateInterval) {
            $ttl = $this->convertDateIntervalToSeconds($ttl);
        }

        $success = true;
        $currentTime = time();

        // Iterate over each key-value pair
        foreach ($values as $key => $value) {
            // Compute the cache file path
            $file = $this->getCacheFilePath($key);

            // If TTL is provided, calculate the expiration time
            $expiresAt = $ttl ? $currentTime + $ttl : 0; // 0 means no expiration

            // Prepare the data to be stored
            $data = [
                'value' => $value,
                'expires_at' => $expiresAt
            ];

            // Serialize the data and write it to the cache file
            $result = file_put_contents($file, serialize($data));

            // If the write operation failed, mark success as false
            if ($result === false) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Convert a DateInterval to seconds.
     *
     * @param DateInterval $interval
     * @return int The number of seconds represented by the DateInterval.
     */
    protected function convertDateIntervalToSeconds(DateInterval $interval): int
    {
        $dateTime = new DateTime();
        $dateTime->add($interval);
        return $dateTime->getTimestamp() - time();
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable<string> $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function deleteMultiple(iterable $keys): bool
    {
        // Ensure that $keys is an array or Traversable
        if (!is_array($keys) && !$keys instanceof \Traversable) {
            throw new InvalidArgumentException('The ' . $keys . ' parameter must be an array or Traversable.');
        }

        $success = true;

        // Iterate over each key
        foreach ($keys as $key) {
            $file = $this->getCacheFilePath($key);

            // If the file exists, attempt to delete it
            if (file_exists($file)) {
                if (!unlink($file)) {
                    // If unlink fails, mark success as false
                    $success = false;
                }
            }
        }

        return $success;
    }
}
