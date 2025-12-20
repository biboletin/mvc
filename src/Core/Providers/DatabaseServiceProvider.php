<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Database\Connection\DsnBuilder;
use Bibo\Mvc\Core\Database\Contracts\PdoDriverInterface;
use Bibo\Mvc\Core\Database\DriverFactory;
use Bibo\Mvc\Core\Database\QueryBuilder;
use Bibo\Mvc\Core\Logger\LogManager;
use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     */
    public function register(): void
    {
        try {
            $dbDriver = config('db.driver');

            // Normalize driver name (pgsql|postgres|postgresql → pgsql)
            $canonical = DriverFactory::resolve($dbDriver);

            // Load config for a normalized key
            $settings = config('db.' . $canonical);
            $settings['driver'] = $canonical;

            // Build driver
            $dsn  = new DsnBuilder();
            $factory = new DriverFactory($dsn, $settings);

            $driver = $factory->create();
            $db = $driver->connect();

            // Register pdo driver in container
            $this->container->set(PdoDriverInterface::class, function () use ($driver) {
                return $driver;
            });

            // Register DB connection in container
            $this->container->set(PDO::class, function () use ($db) {
                return $db;
            });

            $this->container->set(QueryBuilder::class, function () {
                return new QueryBuilder();
            });
        } catch (NotFoundExceptionInterface | ReflectionException | ContainerExceptionInterface $e) {
            // You should handle or log this
            throw $e;
        }
    }

    /**
     * Boot provider
     */
    public function boot(): void
    {
        try {
            $this->container
                ->get(LogManager::class)
                ->get('app')
                ->debug(__CLASS__ . ' booted successfully');
        } catch (NotFoundExceptionInterface | ReflectionException | ContainerExceptionInterface $e) {
        }
    }
}
