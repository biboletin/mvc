<?php

namespace Bibo\Mvc\Core\Cache;

use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Crypto\EncryptException;
use Bibo\Mvc\Core\Traits\EnabledAwareTrait;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;
use Random\RandomException;

/**
 * Simple file caching class
 * implements PSR-16
 */
class FileCache implements CacheInterface
{
    use EnabledAwareTrait;

    /**
     * Cache directory
     *
     * @var string
     */
    protected string $cacheDir;

    /**
     * Cache file
     *
     * @var string
     */
    protected string $cacheFile;

    /**
     * Cache time to live in seconds
     *
     * @var int
     */
    protected int $ttl = 60;

    /**
     * Cache creation time
     *
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $lastModified = null;

    /**
     * Cache expiration time
     *
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $expiresAt = null;

    /**
     * Indicates if the cache was a hit
     *
     * @var bool
     */
    private bool $isHit = false;

    /**
     * Crypto instance for encryption/decryption
     *
     * @var Crypto|null
     */
    private ?Crypto $crypto = null;

    /**
     * Indicates if encryption is used
     *
     * @var bool
     */
    private bool $useEncryption = false;

    /**
     * Use compression
     *
     * @var bool
     */
    private bool $useCompression = false;

    /**
     * Compression type
     *
     * @var string
     */
    private string $compression = 'gzip';

    /**
     * Cache file prefix
     *
     * @var string
     */
    private string $cachePrefix;

    /**
     * DateTime object
     *
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * Constructor
     *
     * @param string $cacheDir
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $cacheDir = '')
    {
        $this->setDateTime(new DateTime());

        if (trim($cacheDir) === '') {
            throw new InvalidArgumentException('Cache directory must be specified.');
        }

        // Ensure the cache directory exists
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        $this->cacheDir = rtrim($cacheDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * Enable or disable encryption
     *
     * @param bool $useEncryption
     *
     * @return void
     */
    public function setEncryption(bool $useEncryption = false): void
    {
        $this->useEncryption = $useEncryption;
    }

    /**
     * Set cache time to live
     *
     * @param int $ttl Time to live in seconds
     *
     * @return void
     */
    public function setTtl(int $ttl): void
    {
        $this->ttl = $ttl;
    }

    /**
     * Set cache file name
     *
     * @param string $cacheFile
     *
     * @return void
     */
    public function setCacheFile(string $cacheFile): void
    {
        $this->cacheFile = $cacheFile;
    }

    /**
     * Set Crypto instance
     *
     * @param Crypto|null $crypto
     *
     * @return void
     */
    public function setCrypto(?Crypto $crypto = null): void
    {
        $this->crypto = $crypto;
    }

    /**
     * Enable or disable compression
     *
     * @param bool $compression
     *
     * @return void
     */
    public function setUseCompression(bool $compression): void
    {
        $this->useCompression = $compression;
    }

    /**
     * Set compression method
     *
     * @param string $compression
     *
     * @return void
     */
    public function setCompression(string $compression): void
    {
        $this->compression = $compression;
    }

    public function setCachePrefix(string $cachePrefix): void
    {
        $this->cachePrefix = $cachePrefix;
    }
    /**
     * Get encryption status
     *
     * @return bool
     */
    public function getEncryption(): bool
    {
        return $this->useEncryption;
    }

    /**
     * Get cache time to live
     *
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * Get cache file name
     *
     * @return string
     */
    public function getCacheFile(): string
    {
        return $this->cacheFile;
    }

    /**
     * Get Crypto instance
     *
     * @return Crypto|null
     */
    public function getCrypto(): ?Crypto
    {
        return $this->crypto;
    }

    /**
     * Get compression status
     *
     * @return bool
     */
    public function getUseCompression(): bool
    {
        return $this->useCompression;
    }

    /**
     * Get compression method
     *
     * @return string
     */
    public function getCompression(): string
    {
        return $this->compression;
    }

    /**
     * Return cache file prefix
     *
     * @return string
     */
    public function getCachePrefix(): string
    {
        return $this->cachePrefix;
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
        $filePath = $this->getCacheFilePath($key);

        // No file? → return default
        if (!file_exists($filePath)) {
            return $default;
        }

        // Expiration check
        $expiresAt = $this->getExpiresAt();
        if ($expiresAt instanceof DateTimeInterface && $expiresAt <= new DateTimeImmutable()) {
            // Cache expired → delete + return default
            unlink($filePath);
            return $default;
        }

        $data = file_get_contents($filePath);
        if ($data === false) {
            return $default;
        }

        // Decompress if enabled
        if ($this->getUseCompression()) {
            $data = $this->uncompress($data);
        }

        // Decrypt if enabled
        if ($this->getEncryption() && $this->getCrypto() !== null) {
            try {
                $data = $this->getCrypto()->decrypt($data);
            } catch (Exception $e) {
                // Corrupt / undecryptable → treat as cache miss
                return $default;
            }
        }

        // Unserialize
        return unserialize($data, ['allowed_classes' => true]) ?: $default;
    }

    /**
     * Store a cache item.
     *
     * @param string                $key   Cache key
     * @param mixed                 $value Cache value
     * @param int|DateInterval|null $ttl   Cache TTL (optional)
     *
     * @return bool True on success, false on failure
     * @throws \DateMalformedStringException
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $data = serialize($value);

        if ($this->getEncryption() && $this->getCrypto() !== null) {
            try {
                $data = $this->getCrypto()->encrypt($data);
            } catch (EncryptException | RandomException $e) {
                return false;
            }
        }

        if ($this->getUseCompression()) {
            $data = $this->compress($data);
        }

        $now = new DateTimeImmutable();

        $this->setLastModified($now);

        if ($ttl instanceof DateInterval) {
            $this->setExpiresAt($now->add($ttl));
        } elseif (is_int($ttl)) {
            $this->setExpiresAt($now->modify('+' . $ttl . ' seconds'));
        } else {
            // no TTL could mean "forever" or "until manually cleared"
            $this->setExpiresAt(null);
        }

        return file_put_contents($this->getCacheFilePath($key), $data) !== false;
    }


    /**
     * Compress data using the specified compression method.
     *
     * @param string $data Data to compress
     *
     * @return string Compressed data
     */
    private function compress(string $data): string
    {
        $compressedData = '';
        switch ($this->getCompression()) {
            case 'gzip':
                $compressedData = gzcompress($data);
                break;
            case 'bzip2':
                $compressedData = bzcompress($data);
                break;
            case 'lz4':
                if (function_exists('lz4_compress')) {
                    $compressedData = lzf_compress($data);
                }
                break;
            case 'zstd':
                if (function_exists('zstd_compress')) {
                    $compressedData = zstd_compress($data);
                }
                break;
            default:
                // Unsupported compression method, return original data
                $compressedData = $data;
                break;
        }
        return $compressedData;
    }

    /**
     * Decompress data using the specified compression method.
     *
     * @param string $data Data to decompress
     *
     * @return string Decompressed data
     */
    private function uncompress(string $data): string
    {
        $compressedData = '';
        switch ($this->getCompression()) {
            case 'gzip':
                $compressedData = gzuncompress($data);
                break;
            case 'bzip2':
                $compressedData = bzdecompress($data);
                break;
            case 'lz4':
                if (function_exists('lz4_compress')) {
                    $compressedData = lzf_decompress($data);
                }
                break;
            case 'zstd':
                if (function_exists('zstd_compress')) {
                    $compressedData = zstd_decompress($data);
                }
                break;
            default:
                // Unsupported compression method, return original data
                $compressedData = $data;
                break;
        }
        return $compressedData;
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
        return $this->cacheDir . $this->getCachePrefix() . hash('xxh3', $key) . '.cache';
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

    public function getLastModified(): ?DateTime
    {
        return $this->lastModified;
    }

    public function setLastModified(?DateTimeImmutable $lastModified): void
    {
        $this->lastModified = $lastModified;
    }

    public function getExpiresAt(): ?DateTime
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTimeImmutable $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function isHit(): bool
    {
        return $this->isHit;
    }

    public function setIsHit(bool $isHit): void
    {
        $this->isHit = $isHit;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
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

    /**
     * Sets cache path
     *
     * @param string $path
     *
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->cacheDir .= $path;
    }

    /**
     * Get the cache path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->cacheDir;
    }
}
