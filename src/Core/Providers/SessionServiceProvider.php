<?php

namespace Bibo\Core\Provider;

use Bibo\Mvc\Core\Providers\ServiceProvider;

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

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
