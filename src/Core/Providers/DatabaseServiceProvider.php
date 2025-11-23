<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Database\Connection\DsnBuilder;
use Bibo\Mvc\Core\Database\DriverFactory;
use Bibo\Mvc\Core\Logger\LogManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     * @throws ReflectionException
     */
    public function register(): void
    {
        try {
            $config = $this->container->get(ConfigHandler::class);

            $settings = [
                'driver' => config('db.driver'),
                'host' => config('db.host'),
                'port' => config('db.port'),
                'database' => config('db.database'),
                'username' => config('db.username'),
                'password' => config('db.password'),
                'charset' => config('db.charset'),
                'collation' => config('db.collation'),
                'prefix' => config('db.prefix'),
                'strict' => config('db.strict'),
                'options' => config('db.options'),
                'timezone' => config('db.timezone'),
                'locale' => config('db.locale'),
                'fallback_locale' => config('db.fallback_locale'),
                'fallback_timezone' => config('db.fallback_timezone'),
                'max_retries' => config('db.max_retries'),
            ];

            $factory = new DriverFactory(new DsnBuilder(), $settings);
            $driver = $factory->create();
            $db = $driver->connect();


dd($config, $factory, $driver, $db);
        } catch (NotFoundExceptionInterface|ReflectionException|ContainerExceptionInterface $e) {
        }
    }

    /**
     * Boot Connection
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        try {
            $this->container
                ->get(LogManager::class)
                ->get('app')
                ->debug(__CLASS__ . ' booted successfully');
        } catch (NotFoundExceptionInterface|ReflectionException|ContainerExceptionInterface $e) {
        }
    }
}
