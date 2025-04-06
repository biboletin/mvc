<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Error\Error;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Psr\Container\NotFoundExceptionInterface;

class ErrorServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->set('error', function () {
            $error = new Error();

            $error->setTemplate($this->container->get('template'));
            $error->register();

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
