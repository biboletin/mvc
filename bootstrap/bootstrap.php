<?php

use Bibo\Core\Provider\AppServiceProvider;
use Bibo\Core\Provider\ConfigServiceProvider;
use Bibo\Core\Provider\ErrorServiceProvider;
use Bibo\Core\Provider\LogServiceProvider;
use Bibo\Core\Provider\RequestServiceProvider;
use Bibo\Core\Provider\RouteServiceProvider;
use Bibo\Mvc\Core\Providers\ServiceProvider;

if (!isset($app)) {
    throw new RuntimeException('App not configured');
}

$container = $app->container();

$providers = [
    ConfigServiceProvider::class,
    LogServiceProvider::class,
    RouteServiceProvider::class,
    AppServiceProvider::class,
    RequestServiceProvider::class,
    ErrorServiceProvider::class,
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
