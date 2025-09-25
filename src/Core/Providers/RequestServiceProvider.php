<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Facades\Request;
use Bibo\Mvc\Core\Logger\LogManager;
use Bibo\Mvc\Core\Request\BaseRequest;
use Psr\Container\NotFoundExceptionInterface;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $request = new BaseRequest($this->container);
        Request::init($request);

        $this->container->set(BaseRequest::class, function () use ($request) {
            return $request;
        });
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container
            ->get(LogManager::class)
            ->get('app')
            ->debug(__CLASS__ . ' booted successfully');
    }
}
