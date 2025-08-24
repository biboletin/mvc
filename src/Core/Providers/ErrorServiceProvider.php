<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Error\Error;
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
        $error = new Error(
            $this->container->get('logger'),
            $this->container->get('config')->get('app.env', 'production')
        );

        $error->setErrorTemplate($this->container->get('template'));
        $error->register();

        $this->container->set('error', function () use ($error) {
            return $error;
        });
    }

    /**
     * Boot service provider
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
