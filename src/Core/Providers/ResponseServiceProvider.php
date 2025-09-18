<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Logger\Logger;
use Bibo\Mvc\Core\Response\BaseResponse;
use Psr\Container\NotFoundExceptionInterface;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $response = new BaseResponse(HttpStatus::OK->value, []);

        $this->container->set(BaseResponse::class, function () use ($response) {
            return $response;
        });
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
