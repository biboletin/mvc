<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Response\BaseResponse;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->set('response', fn () => new BaseResponse());
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
