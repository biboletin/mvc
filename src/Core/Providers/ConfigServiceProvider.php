<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Config\Config;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Dotenv\Dotenv;

class ConfigServiceProvider extends ServiceProvider
{
    private const string CACHE_FILE = CACHE_PATH . '/config/config.php';

    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $dotenv = Dotenv::createImmutable(ROOT_PATH);
        $dotenv->load();

        $config = new Config();
        $config->parseFromEnv();

        // Check if cache exists and is still valid
        if (file_exists(self::CACHE_FILE)) {
            $cachedData = json_decode(file_get_contents(self::CACHE_FILE), true);

            if (is_array($cachedData) && isset($cachedData['timestamp'])) {
                $cacheAge = time() - $cachedData['timestamp'];

                if ($cacheAge < $_ENV['CACHE_TTL']) {
                    $config->merge($cachedData['config']);
                } else {
                    unlink(self::CACHE_FILE);
                }
            }
        }

        // If cache does not exist or was deleted, regenerate it
        if (!file_exists(self::CACHE_FILE)) {
            $configs = glob(ROOT_PATH . 'config/*.php');

            foreach ($configs as $file) {
                $config->load($file);
            }

            // Save config with timestamp
            $cacheData = [
                'timestamp' => time(),
                'config' => $config->all()
            ];

            file_put_contents(self::CACHE_FILE, json_encode($cacheData));
        }

        $this->container->set('config', fn () => $config);
    }



    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
