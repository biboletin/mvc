<?php

namespace Bibo\Core\Provider;

use Bibo\Core\View\View;
use Bibo\Mvc\Core\Providers\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->set('views', fn () => new View());
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
