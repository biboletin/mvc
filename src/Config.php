<?php

namespace Biboletin\Mvc;

/**
 * Config class
 */
class Config
{
    /**
     * Config array
     *
     * @var array<string>
     */
    private array $config;
    /**
     * Config files path
     *
     * @var string
     */
    private const CONFIG_FILES_PATH = '../config/*.php';

    /**
     * Constructor
     */
    public function __construct()
    {
        $config = [];
        $files = glob(self::CONFIG_FILES_PATH);
        foreach ($files as $file) {
            if (is_file($file) === false) {
                continue;
            }
            $key = pathinfo($file, PATHINFO_FILENAME);
            $config[$key] = include_once($file);
        }
        $this->config = $config;
    }

    /**
     * Get config setting by key
     * Keys must be in format: main_key.sub_key
     *
     * @param string      $key
     * @param string|null $default
     *
     * @return string
     */
    public function get(string $key, ?string $default = null): string
    {
        $explode = preg_split('/[.]/', $key);
        $configKey = $explode[0];
        $configKeyKey = $explode[1];

        if (array_key_exists($configKey, $this->config) === false) {
            return $default ?? '';
        }

        if (array_key_exists($configKeyKey, $this->config[$configKey]) === false) {
            return $default ?? '';
        }

        return $this->config[$configKey][$configKeyKey];
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->config = [];
    }
}
