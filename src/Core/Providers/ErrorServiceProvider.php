<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Error\Error;
use Bibo\Mvc\Core\Error\ErrorResponseFactory;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Facades\Env;
use Bibo\Mvc\Core\Logger\LogManager;
use Bibo\Mvc\Core\View\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class ErrorServiceProvider extends ServiceProvider
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
        // Register ErrorResponseFactory
        $this->container->set(ErrorResponseFactory::class, fn () => new ErrorResponseFactory());
        // Register Error service (for normalization and logging)
        $this->container->set(Error::class, fn () => new Error());
    }

    /**
     * Boot service provider
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function boot(): void
    {
        $view = $this->container->has(View::class)
            ? $this->container->get(View::class)
            : null;

        $responseFactory = $this->container->get(ErrorResponseFactory::class);
        $responseFactory->setView($view);
        $responseFactory->setDebug(Env::isDevelopment());

        $error = $this->container->get(Error::class);
        $error->setContainer($this->container);
        $error->setLogger($this->container->get(LogManager::class)->get('app'));
    }
}
