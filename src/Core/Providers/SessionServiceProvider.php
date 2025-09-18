<?php

namespace Bibo\Mvc\Core\Providers;

use Psr\Container\NotFoundExceptionInterface;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        // TODO: Implement register() method.
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
