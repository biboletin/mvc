<?php

namespace Bibo\Mvc\Core\Cache;

use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Traits\EnabledAwareTrait;
use Psr\SimpleCache\CacheInterface;

/**
 * Empty cache
 */
class NullCache implements CacheInterface
{
    use EnabledAwareTrait;

    /**
     * Returns default
     *
     * @param string $key
     * @param $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        return $default;
    }

    /**
     * Returns true
     *
     * @param string $key
     * @param $value
     * @param $ttl
     *
     * @return bool
     */
    public function set(string $key, $value, $ttl = null): bool
    {
        return true;
    }

    /**
     * Returns true
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        return true;
    }

    /**
     * Returns true
     *
     * @return bool
     */
    public function clear(): bool
    {
        return true;
    }

    /**
     * Returns empty array
     *
     * @param $keys
     * @param $default
     *
     * @return iterable
     */
    public function getMultiple($keys, $default = null): iterable
    {
        return [];
    }

    /**
     * Returns true
     *
     * @param $values
     * @param $ttl
     *
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool
    {
        return true;
    }

    /**
     * Returns true
     *
     * @param $keys
     *
     * @return bool
     */
    public function deleteMultiple($keys): bool
    {
        return true;
    }

    /**
     * Returns false
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        return false;
    }

    /**
     * Sets crypto
     *
     * @param Crypto $crypto
     *
     * @return void
     */
    public function setCrypto(Crypto $crypto): void
    {
    }

    /**
     * Sets encryption
     *
     * @param bool $encryption
     *
     * @return void
     */
    public function setEncryption(bool $encryption): void
    {
    }

    /**
     * Sets TTL
     *
     * @param int $ttl
     *
     * @return void
     */
    public function setTtl(int $ttl): void
    {
    }

    /**
     * Sets compression usage
     *
     * @param bool $useCompression
     *
     * @return void
     */
    public function setUseCompression(bool $useCompression): void
    {
    }

    /**
     * Sets compression
     *
     * @param string $compression
     *
     * @return void
     */
    public function setCompression(string $compression): void
    {
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
    }

    /**
     * Get a cache path
     *
     * @return void
     */
    public function getPath(): void
    {
    }

    public function setCachePrefix(string $prefix): void
    {
    }

    public function getCachePrefix(): void
    {
    }
}
