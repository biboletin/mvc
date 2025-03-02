<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Error\Error;
use Bibo\Mvc\Core\Providers\ServiceProvider;

class ErrorServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        Error::register();
    }
}
