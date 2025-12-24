<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Response\BaseResponse;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->container->set(BaseResponse::class, fn () => new BaseResponse());
    }

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
