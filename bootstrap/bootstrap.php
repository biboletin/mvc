<?php

use Bibo\Mvc\Core\Providers\AppServiceProvider;
use Bibo\Mvc\Core\Providers\ConfigServiceProvider;
use Bibo\Mvc\Core\Providers\ControllerServiceProvider;
use Bibo\Mvc\Core\Providers\ErrorServiceProvider;
use Bibo\Mvc\Core\Providers\FileCacheServiceProvider;
use Bibo\Mvc\Core\Providers\LogServiceProvider;
use Bibo\Mvc\Core\Providers\MiddlewareDispatcherServiceProvider;
use Bibo\Mvc\Core\Providers\MiddlewareServiceProvider;
use Bibo\Mvc\Core\Providers\ModelServiceProvider;
use Bibo\Mvc\Core\Providers\RequestServiceProvider;
use Bibo\Mvc\Core\Providers\RouteServiceProvider;
use Bibo\Mvc\Core\Providers\TemplateServiceProvider;
use Bibo\Mvc\Core\Providers\ViewServiceProvider;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use RuntimeException;

if (!isset($app)) {
    throw new RuntimeException('App not configured');
}

$container = $app->container();

// Core service providers
$providers = [
    ConfigServiceProvider::class,
    TemplateServiceProvider::class,
    LogServiceProvider::class,
    ErrorServiceProvider::class,
    MiddlewareDispatcherServiceProvider::class,
    MiddlewareServiceProvider::class,
    AppServiceProvider::class,
    RouteServiceProvider::class,
    RequestServiceProvider::class,
    ControllerServiceProvider::class,
    ModelServiceProvider::class,
    FileCacheServiceProvider::class,
    ViewServiceProvider::class,
];

$registeredProviders = [];
foreach ($providers as $providerClass) {
    $provider = new $providerClass($container);
    $provider->register();
    $registeredProviders[] = $provider;
}

foreach ($registeredProviders as $provider) {
    if ($provider instanceof ServiceProvider) {
        $provider->boot();
    }
}
