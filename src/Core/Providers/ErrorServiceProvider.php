<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Error\Error;
use Bibo\Mvc\Core\Error\ErrorResponseFactory;
use Bibo\Mvc\Core\Facades\Env;
use Bibo\Mvc\Core\Logger\Logger;
use Bibo\Mvc\Core\View\View;
use Psr\Container\NotFoundExceptionInterface;

class ErrorServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        // Register ErrorResponseFactory (can inject view and debug flag)
        $this->container->set(ErrorResponseFactory::class, function ($container) {
            $view = $container->has(View::class) ? $container->get(View::class) : null;
            $debug = Env::get()->value === 'development';

            return new ErrorResponseFactory($view, $debug);
        });

        // Register Error service (for normalization and logging)
        $this->container->set(Error::class, function ($container) {
            return new Error(
                $container
            );
        });
    }

    /**
     * Boot service provider
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
