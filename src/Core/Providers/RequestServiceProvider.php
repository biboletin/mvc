<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Request\BaseRequest;
use Bibo\Mvc\Core\Providers\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->set('request', fn () => new BaseRequest());
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
