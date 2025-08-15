<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Providers\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
