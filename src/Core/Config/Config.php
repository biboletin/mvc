<?php

namespace Bibo\Mvc\Core\Config;

use Bibo\Mvc\Core\Cache\FileCache;
use Bibo\Mvc\Core\Interfaces\ConfigInterface;
use Exception;
use RuntimeException;

/**
 * Configuration class for managing application settings and options.
 *
 * This class provides methods for loading, accessing, and managing configuration
 * values from various sources. It supports loading from PHP files, environment
 * files, and caching for improved performance.
 *
 * @package Bibo\Core\Config
 */
class Config implements ConfigInterface
{
    /**
     * Configuration array that stores all loaded configuration values.
     *
     * @var array<string, mixed>
     */
    private array $config = [];

    /**
     * Cache handler for configuration.
     *
     * @var FileCache|null
     */
    private ?FileCache $cache;

    /**
     * The configuration path.
     *
     * @var string
     */
    private string $configPath;

    /**
     * The cache path.
     *
     * @var string
     */
    private string $cachePath;

    /**
     * Constructor for Config class.
     *
     * @param FileCache|null $cache      Optional FileCache instance for caching
     * @param string|null    $configPath Path to configuration files directory
     * @param string|null    $cachePath  Path to cache directory
     */
    public function __construct(?FileCache $cache = null, ?string $configPath = null, ?string $cachePath = null)
    {
        $this->cache = $cache;
        $this->configPath = $configPath
            ?? (defined('CONFIG_PATH')
                ? CONFIG_PATH
                : __DIR__ . '/../../config/');
        $this->cachePath = $cachePath ?? (defined('CACHE_PATH')
            ? CACHE_PATH . 'config/'
            : __DIR__ . '/../../cache/config/');

        // Ensure the cache directory exists
        if (!is_null($this->cache) && !file_exists($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
        }
    }

    /**
     * Get a configuration value by key.
     *
     * Retrieves a value from the configuration array using the specified key.
     * If the key does not exist, returns the default value.
     *
     * @param string $key The configuration key to retrieve
     * @param string|null $default The default value to return if the key doesn't exist
     *
     * @return mixed The configuration value or default if not found
     */
    public function get(string $key, mixed $default = null): mixed
    {
        // Normalize key
        $key = trim(strtoupper($key));

        // if is UPPER_CASE (ENV style)
        if (preg_match('/^[A-Z0-9_]+$/', $key)) {
            $segments = explode('_', strtolower($key));
        } else {
            // dot notation
            $segments = explode('.', strtolower($key));
        }

        $value = $this->config;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }



    /**
     * Check if a configuration key exists.
     *
     * Determines whether the specified key exists in the configuration array.
     *
     * @param string $key The configuration key to check
     *
     * @return bool True if the key exists, false otherwise
     */
    public function has(string $key): bool
    {
        $formattedKey = trim(strtoupper($key));
        return array_key_exists($formattedKey, $this->config);
    }

    /**
     * Get all configuration values.
     *
     * Retrieves all configuration values from the configuration array.
     * If the configuration is cached, it will load the values from the cache.
     *
     * @return array<string, mixed> All configuration values
     */
    public function all(): array
    {
        if (empty($this->config)) {
            $this->loadFromCache();
        }
        return $this->config;
    }

    /**
     * Attempt to load configuration from cache.
     *
     * @return bool True if loaded from cache, false otherwise
     */
    private function loadFromCache(): bool
    {
        // Try loading from FileCache first if available
        if ($this->cache !== null && $this->cache->has('config')) {
            $cachedConfig = $this->cache->get('config');
            if (is_array($cachedConfig)) {
                $this->config = $cachedConfig;
                return true;
            }
        }

        // Fall back to file-based cache
        $cacheFile = $this->cachePath . 'config.php';
        if (file_exists($cacheFile)) {
            $config = include $cacheFile;
            if (is_array($config)) {
                $this->config = $config;
                return true;
            }
        }

        return false;
    }

    /**
     * Load configuration files from the config directory.
     *
     * Scans the config directory for PHP files and loads them into the configuration array.
     * Each file should return an array of configuration values. Also caches the configuration.
     *
     * @return void
     */
    public function load(): void
    {
        // Try to load from the cache first
        // if ($this->loadFromCache()) {
        //     return;
        // }

        $configFiles = glob($this->configPath . '*.php');
        if ($configFiles === false) {
            throw new RuntimeException("Failed to read configuration directory: {$this->configPath}");
        }

        $config = [];
        foreach ($configFiles as $file) {
            $name = basename($file, '.php');
            $this->config = $config;
            $contents = include $file;

            if (is_array($contents)) {
                // If the file returns an array with a top-level key that matches the filename
                if (count($contents) === 1 && isset($contents[$name])) {
                    $config[$name] = $contents[$name];
                } else {
                    $config[$name] = $contents;
                }
            }
            $this->config = $config;
        }

        $this->format($config);
        $this->cacheConfig();
    }

    /**
     * Format configuration values and store them in the config array.
     *
     * Processes the configuration array to standardize the format of values
     * and stores them in the config property.
     *
     * @param array<string, array<string, mixed>> $config The configuration array to format
     *
     * @return void
     */
    private function format(array $config): void
    {
        // dd($config);
        foreach ($config as $key => $value) {
            if (!is_array($value)) {
                continue;
            }
            $formattedKey = trim(strtolower($key));
            $this->config[$formattedKey] = $value;
        }
    }

    /**
     * Cache the current configuration.
     *
     * @return bool True on success, false on failure
     */
    private function cacheConfig(): bool
    {
        // Try to cache using FileCache if available
        if ($this->cache !== null) {
            return $this->cache->set('config', $this->config);
        }

        // Fall back to file-based caching
        $cachePath = $this->cachePath;

        // Ensure the cache directory exists
        if (!file_exists($cachePath)) {
            if (!mkdir($cachePath, 0777, true) && !is_dir($cachePath)) {
                return false;
            }
        }

        $cacheFile = $cachePath . 'config.php';

        try {
            $result = file_put_contents(
                $cacheFile,
                "<?php\n\nreturn " . var_export($this->config, true) . ";\n",
                LOCK_EX
            );
            return $result !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Load a configuration file.
     *
     * Loads configuration values from the specified file.
     * The file should contain key-value pairs in the format "key=value".
     * Comments starting with # are ignored.
     *
     * @param string $file The path to the configuration file
     *
     * @return void
     * @throws RuntimeException If the file does not exist or cannot be read
     */
    public function loadFromFile(string $file): void
    {
        if (!file_exists($file)) {
            throw new RuntimeException('Configuration file not found: ' . $file);
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            throw new RuntimeException('Failed to read configuration file: ' . $file);
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, null);
            $key = trim(strtoupper($key));
            $value = trim($value);

            if (in_array(strtolower($value), ['true', 'false', 'null'], true)) {
                $value = strtolower($value) === 'true';
            } elseif (is_numeric($value)) {
                $value = $value + 0;
            } elseif (preg_match('/^["\'](.*)["\']$/', $value, $matches)) {
                $value = $matches[1];
            }

            // $this->config[$key] = $value;
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv("$key=$value");
        }

        $this->cacheConfig();
    }

    /**
     * Convert configuration values to PHP constants.
     *
     * Iterates through all configuration values and defines them as PHP constants.
     * The constant names are uppercase versions of the configuration keys.
     * If a constant with the same name already exists, it will be skipped.
     *
     * @return void
     */
    public function toConstants(): void
    {
        if (empty($this->config)) {
            return;
        }

        foreach ($this->config as $key => $value) {
            $name = strtoupper(trim($key));

            if (defined($name)) {
                continue;
            }

            if (is_array($value)) {
                $value = var_export($value, true);
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } elseif ($value === null) {
                $value = 'null';
            } else {
                $value = var_export($value, true);
            }

            define($name, $value);
        }
    }

    /**
     * Set a configuration value.
     *
     * @param string $key   The configuration key
     * @param mixed  $value The configuration value
     *
     * @return $this
     */
    public function set(string $key, mixed $value): self
    {
        $formattedKey = trim(strtoupper($key));
        $this->config[$formattedKey] = $value;

        // Update cache
        $this->cacheConfig();

        return $this;
    }

    /**
     * Merge configuration arrays.
     *
     * @param array $config Configuration to merge with existing config
     *
     * @return $this
     */
    public function merge(array $config): self
    {
        $this->config = array_merge($this->config, $config);

        // Update cache
        $this->cacheConfig();

        return $this;
    }

    /**
     * Clear all configuration values.
     *
     * @return $this
     */
    public function clear(): self
    {
        $this->config = [];

        // Update cache
        $this->cacheConfig();

        return $this;
    }
}
