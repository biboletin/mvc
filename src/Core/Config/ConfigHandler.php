<?php

namespace Bibo\Mvc\Core\Config;

use Bibo\Mvc\Core\Exception\Custom\Application\ConfigException;
use Bibo\Mvc\Core\Interfaces\ConfigInterface;
use Exception;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Configuration class for managing application settings and options.
 *
 * This class provides methods for loading, accessing, and managing configuration
 * values from various sources. It supports loading from PHP files, environment
 * files, and caching for improved performance.
 */
class ConfigHandler implements ConfigInterface
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
     * @var CacheInterface|null
     */
    private ?CacheInterface $cache;

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
     */
    public function __construct()
    {
    }

    /**
     * Set the file caching handler.
     *
     * @param CacheInterface $cache The file cache instance to use for caching configuration
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function setFileCaching(CacheInterface $cache): void
    {
        $this->cache = $cache;
        $this->cache->set('config', $this->config);
    }

    /**
     * Set the configuration path.
     *
     * @param string|null $configPath The path to the configuration directory
     *
     * @return void
     */
    public function setConfigPath(?string $configPath = null): void
    {
        $this->configPath = $configPath
            ?? (defined('CONFIG_PATH')
                ? CONFIG_PATH
                : __DIR__ . '/../../config/');
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
        // normalize key
        $segments = explode('.', trim($key));

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
        $segments = explode('.', trim($key));

        $value = $this->config;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return false;
            }
            $value = $value[$segment];
        }

        return true;
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
        // if (empty($this->config)) {
        //     $this->loadFromCache();
        // }

        return $this->config;
    }

    /**
     * Load configuration files from the config directory.
     *
     * Scans the config directory for PHP files and loads them into the configuration array.
     * Each file should return an array of configuration values. Also caches the configuration.
     *
     * @return void
     * @throws ConfigException
     */
    public function load(): void
    {
        // Try to load from the cache first
        // if ($this->loadFromCache()) {
        //     return;
        // }

        $configFiles = glob($this->configPath . '*.php') ?? [];
        if ($configFiles === false) {
            throw new ConfigException('Failed to read configuration directory: ' . $this->configPath);
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
        // $this->cacheConfig();
    }

    /**
     * Attempt to load configuration from cache.
     *
     * @return bool True if loaded from cache, false otherwise
     *
     * @throws InvalidArgumentException
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
     *
     * @throws InvalidArgumentException
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
     *
     * @throws ConfigException If the file does not exist or cannot be read
     */
    public function loadFromFile(string $file): void
    {
        if (!file_exists($file)) {
            throw new ConfigException('Configuration file not found: ' . $file);
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            throw new ConfigException('Failed to read configuration file: ' . $file);
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

            putenv($key . '=' . $value);
        }

        // $this->cacheConfig();
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
}
