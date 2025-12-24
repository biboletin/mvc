<?php

/**
 * Cache configuration
 * enabled: Enable or disable caching
 * driver: Cache driver to use (file, redis, memcached, etc.)
 * prefix: Prefix for cache keys
 * ttl: Time to live for cache items in seconds
 * path: Path for file-based cache
 * enable_compression: Enable or disable compression for cached data
 * compression: Compression algorithm to use (gzip, bzip2, etc.)
 * encryption: Enable or disable encryption for cached data
 */

return [

    /*
     * Enable or disable caching
     */

    'enabled' => get_env('cache.enabled', false),

    /*
     * Cache driver to use (file, redis, memcached, etc.)
     */

    'driver' => get_env('cache.driver', 'file'),

    /*
     * Prefix for cache keys
     */

    'prefix' => get_env('cache.prefix', 'mvc_cache_'),

    /*
     * Time to live for cache items in seconds
     */

    'ttl' => get_env('cache.ttl', 60),

    /*
     * Path for file-based cache
     */

    'path' => get_env('cache.path', sys_get_temp_dir() . '/mvc/cache/'),

    /*
     * Enable or disable compression for cached data
     */

    'enable_compression' => get_env('cache.enable_compression', false),

    /*
     * Compression algorithm to use (gzip, bzip2, etc.)
     */

    'compression' => get_env('cache.compression', 'gzip'),

    /*
     * Enable or disable encryption for cached data
     */

    'encryption' => get_env('cache.encryption', false),
];
