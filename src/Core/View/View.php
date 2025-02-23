<?php

namespace Bibo\Core\View;

use Bibo\Core\Cache\FileCache;
use RuntimeException;

class View
{
    protected static array $data = [];
    protected string $view;

    protected FileCache $cache;

    public function __construct()
    {
        $this->cache = new FileCache(CACHE_PATH . 'app/');
    }

    /**
     * Renders a view and caches the output if not cached.
     *
     * @param string     $view The view file to render
     * @param array|null $data The data to pass to the view
     *
     * @return string The rendered view
     * @throws RuntimeException if the view file does not exist
     */
    public function render(string $view, ?array $data): string
    {
        $viewPath = VIEW_PATH . $view . '.php';
        $cacheKey = $this->getCacheKey($view, $data);

        // Check if the view is cached
        if ($cachedContent = $this->cache->get($cacheKey)) {
            return $cachedContent;
        }

        if (!file_exists($viewPath)) {
            throw new RuntimeException("View file '" . $view . "' not found.");
        }

        ob_start(); // Start output buffering
        extract($data); // Extract data variables for use in the template
        include $viewPath; // Include the view file
        $output = ob_get_clean(); // Get the buffer content and clean it

        // Cache the output for future requests
        $this->cache->set($cacheKey, $output, 3600); // Cache for 1 hour (optional TTL)

        return $output;
    }

    /**
     * Generates a unique cache key based on the view name and data.
     *
     * @param string $view The view file name
     * @param array|null $data The data for the view
     * @return string The cache key
     */
    protected function getCacheKey(string $view, ?array $data): string
    {
        // Generate a hash of the view and data to create a unique cache key
        return md5($view . serialize($data));
    }

    public function __get($key)
    {
        return self::$data[$key];
    }

    public function __set($key, $value)
    {
        self::$data[$key] = $value;
    }

    public function __isset($key)
    {
        return isset(self::$data[$key]);
    }

    public function __unset($key)
    {
        unset(self::$data[$key]);
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __invoke(): string
    {
        return $this->render();
    }

    public function __call($method, $args)
    {
        if (isset(self::$data[$method]) && is_callable(self::$data[$method])) {
            return call_user_func_array(self::$data[$method], $args);
        }
    }

    public static function __callStatic($method, $args)
    {
        if (isset(self::$data[$method]) && is_callable(self::$data[$method])) {
            return call_user_func_array(self::$data[$method], $args);
        }
    }

    public function __debugInfo()
    {
        return self::$data;
    }

    public function __sleep()
    {
        return ['data'];
    }

    public function __wakeup()
    {
        // do nothing
    }

    public function __clone()
    {
        // do nothing
    }

    public function renderPartial($view, $data = [])
    {
        $view = new $view($data);
        return $view->render();
    }

    public function __destruct()
    {
        self::$data = [];
    }
}
