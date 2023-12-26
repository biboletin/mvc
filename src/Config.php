<?php

namespace Biboletin\Mvc;

/**
 *
 */
class Config
{
    /**
     * @var array<string>
     */
    private array $config;

    /**
     *
     */
    public function __construct()
    {
        $config = [];
        $files = glob('../config/*.php');
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            $key = pathinfo($file, PATHINFO_FILENAME);
            $config[$key] = include_once($file);
        }
        $this->config = $config;
    }

    /**
     * @param string      $keys
     * @param string|null $default
     *
     * @return string
     */
    public function get(string $keys, ?string $default = null): string
    {
        $explode = preg_split('/[.]/', $keys);
        $configKey = $explode[0];
        $configKeyKey = $explode[1];

        if (!array_key_exists($configKey, $this->config)) {
            return $default ?? '';
        }

        if (!array_key_exists($configKeyKey, $this->config[$configKey])) {
            return $default ?? '';
        }

        return $this->config[$configKey][$configKeyKey];
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->config = [];
    }
}
