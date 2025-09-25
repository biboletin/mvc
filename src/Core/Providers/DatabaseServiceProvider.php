<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Logger\LogManager;
use Psr\Container\NotFoundExceptionInterface;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->set('db', fn () => null);
    }

    /**
     * Boot Database
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container
            ->get(LogManager::class)
            ->get('app')
            ->debug(__CLASS__ . ' booted successfully');
    }
}
