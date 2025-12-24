<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Database\Connection\DsnBuilder;
use Bibo\Mvc\Core\Database\Contracts\PdoDriverInterface;
use Bibo\Mvc\Core\Database\DriverFactory;
use Bibo\Mvc\Core\Database\QueryBuilder;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use PDO;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->container->set(DsnBuilder::class, fn () => new DsnBuilder());
        $this->container->set(DriverFactory::class, fn () => new DriverFactory());

        // Register pdo driver in container
        $this->container->set(PdoDriverInterface::class, function ($container) {
            $config = $container->get(ConfigHandler::class);
            $driverName = $config->get('db.driver');

            // Normalize driver name (pgsql|postgres|postgresql → pgsql)
            $driverFactory = $container->get(DriverFactory::class);
            $canonical = $driverFactory->resolve($driverName);

            // Load config for a normalized key
            $settings = $config->get('db.' . $canonical);
            $settings['driver'] = $canonical;

            // Build driver
            $dsn = $container->get(DsnBuilder::class);

            $factory = $container->get(DriverFactory::class);
            $factory->setDsn($dsn);
            $factory->setConfig($settings);

            return $factory->create();
        });

        // Register DB connection in container
        $this->container->set(PDO::class, function ($container) {
            $driver = $container->get(PdoDriverInterface::class);

            return $driver->connect();
        });

        $this->container->set(QueryBuilder::class, fn () => new QueryBuilder());
    }

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
