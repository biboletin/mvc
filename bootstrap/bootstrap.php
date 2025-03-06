<?php

use Bibo\Core\Provider\AppServiceProvider;
use Bibo\Core\Provider\ConfigServiceProvider;
use Bibo\Core\Provider\ControllerServiceProvider;
use Bibo\Core\Provider\ErrorServiceProvider;
use Bibo\Core\Provider\FileCacheServiceProvider;
use Bibo\Core\Provider\LogServiceProvider;
use Bibo\Core\Provider\ModelServiceProvider;
use Bibo\Core\Provider\RequestServiceProvider;
use Bibo\Core\Provider\RouteServiceProvider;
use Bibo\Core\Provider\ViewServiceProvider;
use Bibo\Mvc\Core\Providers\ServiceProvider;

if (!isset($app)) {
    throw new RuntimeException('App not configured');
}

$container = $app->container();

$providers = [
    ConfigServiceProvider::class,
    LogServiceProvider::class,
    ErrorServiceProvider::class,
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
