<?php

namespace Bibo\Core\Config;

use Bibo\Core\Cache\FileCache;
use Bibo\Core\Interfaces\ConfigInterface;
use RuntimeException;

/**
 * Configuration class for managing application settings and options.
 *
 * TODO: Add support for loading configuration from different sources (e.g., database, API)
 * TODO: Load configuration files from a specific directory
 * TODO: Add support for environment-specific configuration files
 * TODO: Add support for configuration caching
 * TODO: Add support for configuration validation
 * TODO: Add support for configuration merging
 * TODO: Add support for configuration overrides
 * TODO: Add support for configuration serialization
 */
class Config implements ConfigInterface
{
    /**
     * Configuration array
     *
     * @var array
     */
    private static array $config = [];
    private static FileCache $cache;

    /**
     * Get a configuration value
     *
     * @param string $key
     * @param string|null $default
     *
     * @return mixed
     */
    public static function get(string $key, ?string $default = null): mixed
    {
        return self::$config[trim(strtoupper($key))] ?? $default;
    }

    /**
     * Check if a configuration key exists
     *
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key): bool
    {
        return array_key_exists($key, self::$config);
    }

    /**
     * Get all configuration values
     *
     * @return array
     */
    public static function all(): array
    {
        if (file_exists(CONFIG_CACHE_PATH . 'config.php')) {
            $config = include CONFIG_CACHE_PATH . 'config.php';

            if (is_array($config)) {
                self::$config = $config;
            }
        }
        return !empty(self::$config) ? self::$config : [];
    }

    public static function load(): void
    {
        $configFiles = glob(CONFIG_PATH . '*.php');

        $config = [];
        foreach ($configFiles as $file) {
            $name = basename($file, '.php');
            $contents = include $file;

            if (is_array($contents)) {
                // Flatten if the array has only one top-level key (e.g., 'app' => [...])
                if (count($contents) === 1 && isset($contents[$name])) {
                    $config[$name] = $contents[$name];
                } else {
                    $config[$name] = $contents;
                }
            }
        }

        self::format($config);
    }

    private static function format(array $config): void
    {
        foreach ($config as $name => $conf) {
            foreach ($conf as $key => $value) {
                $key = trim(strtoupper($name . '_' . $key));
                if (is_array($value)) {
                    self::$config[$key] = $value;
                } elseif (is_bool($value)) {
                    self::$config[$key] = $value ? 'true' : 'false';
                } elseif ($value === null) {
                    self::$config[$key] = 'null';
                } else {
                    self::$config[$key] = $value;
                }
            }
        }

        if (file_exists(CACHE_PATH . '/config/config.php')) {
            $cache = CACHE_PATH . 'config/config.php';
            file_put_contents(
                $cache,
                "<?php\n\nreturn " . var_export(self::$config, true) . ";\n",
                LOCK_EX
            );
        }
    }
    /**
     * Load a configuration file
     *
     * @param string $file
     *
     * @return void
     * @throws RuntimeException
     */
    public static function loadFromFile(string $file): void
    {
        if (!file_exists($file)) {
            throw new RuntimeException('Configuration file not found: ' . $file);
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, null);
            $key = trim($key);
            $value = trim($value);

            if (in_array(strtolower($value), ['true', 'false', 'null'], true)) {
                $value = strtolower($value) === 'true';
            } elseif (is_numeric($value)) {
                $value = $value + 0;
            } elseif (preg_match('/^["\'](.*)["\']$/', $value, $matches)) {
                $value = $matches[1];
            }
            self::$config[$key] = $value;

            if (file_exists(CACHE_PATH . '/config/config.php')) {
                $cache = CACHE_PATH . 'config/config.php';
                file_put_contents(
                    $cache,
                    "<?php\n\nreturn " . var_export(self::$config, true) . ";\n",
                    LOCK_EX
                );
            }
        }
    }

    /**
     * Convert configuration values to constants
     *
     * @return void
     */
    public function toConstants(): void
    {
        if (empty(self::$config)) {
            return;
        }

        foreach (self::$config as $key => $value) {
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
