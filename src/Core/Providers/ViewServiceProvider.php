<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Logger\LogManager;
use Bibo\Mvc\Core\View\View;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class ViewServiceProvider
 * This class is responsible for registering the view service in the container.
 * It creates an instance of the View class and binds it to the container.
 * It also provides a boot method to log the successful booting of the service.
 *
 * @package Bibo\Core\Providers
 */
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     * @throws Exception
     */
    public function register(): void
    {
        try {
            $view = new View($this->container);

            $this->container->set(View::class, function () use ($view) {
                return $view;
            });
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Boot the service provider
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
