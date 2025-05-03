<?php

use Bibo\Core\Provider\AppServiceProvider;
use Bibo\Core\Provider\ConfigServiceProvider;
use Bibo\Core\Provider\ControllerServiceProvider;
use Bibo\Core\Provider\ErrorServiceProvider;
use Bibo\Core\Provider\FileCacheServiceProvider;
use Bibo\Core\Provider\LogServiceProvider;
use Bibo\Core\Provider\MiddlewareDispatcherServiceProvider;
use Bibo\Core\Provider\MiddlewareServiceProvider;
use Bibo\Core\Provider\ModelServiceProvider;
use Bibo\Core\Provider\RequestServiceProvider;
use Bibo\Core\Provider\RouteServiceProvider;
use Bibo\Core\Provider\TemplateServiceProvider;
use Bibo\Core\Provider\ViewServiceProvider;
use Bibo\Mvc\Core\Providers\ServiceProvider;

if (!isset($app)) {
    throw new RuntimeException('App not configured');
}

$container = $app->container();

$providers = [
    // Core service providers
    ConfigServiceProvider::class,
    LogServiceProvider::class,
    ErrorServiceProvider::class,
    // Utility service providers
    TemplateServiceProvider::class,
    ViewServiceProvider::class,
    FileCacheServiceProvider::class,
    // Middleware service providers
    MiddlewareDispatcherServiceProvider::class,
    MiddlewareServiceProvider::class,
    // HTTP service providers
    RequestServiceProvider::class,
    ControllerServiceProvider::class,
    RouteServiceProvider::class,
    // Application service providers
    ModelServiceProvider::class,
    AppServiceProvider::class,
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
