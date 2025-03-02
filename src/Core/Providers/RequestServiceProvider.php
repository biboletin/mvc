<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Request\BaseRequest;
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
}
