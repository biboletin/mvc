<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Facades\Request;
use Bibo\Mvc\Core\Request\BaseRequest;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class RequestServiceProvider extends ServiceProvider
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
        $this->container->set(BaseRequest::class, fn () => new BaseRequest());
    }

    /**
     * Boot the service provider
     *
     * @return void
     *
     * @throws NotFoundExceptionInterface If no entry is found for the identifier.
     * @throws ContainerExceptionInterface If resolving the entry fails.
     * @throws ReflectionException
     */
    public function boot(): void
    {

        $request = $this->container->get(BaseRequest::class);

        Request::init($request);
    }
}
