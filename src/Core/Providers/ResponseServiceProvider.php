<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Response\BaseResponse;
use Bibo\Mvc\Core\Providers\ServiceProvider;

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
}
