<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Facades\Request;
use Bibo\Mvc\Core\Request\BaseRequest;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $request = new BaseRequest();
        Request::init($request);

        $this->container->set('request', function () use ($request) {
            return $request;
        });
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
