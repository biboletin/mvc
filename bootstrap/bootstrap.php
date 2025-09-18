<?php

use Bibo\Mvc\Core\Providers\CookieJarServiceProvider;
use Bibo\Mvc\Core\Providers\CookieServiceProvider;
use Bibo\Mvc\Core\Providers\EnumServiceProvider;
use Bibo\Mvc\Core\Providers\AppServiceProvider;
use Bibo\Mvc\Core\Providers\ConfigServiceProvider;
use Bibo\Mvc\Core\Providers\ControllerServiceProvider;
use Bibo\Mvc\Core\Providers\CryptoServiceProvider;
use Bibo\Mvc\Core\Providers\ErrorServiceProvider;
use Bibo\Mvc\Core\Providers\FileCacheServiceProvider;
use Bibo\Mvc\Core\Providers\LogServiceProvider;
use Bibo\Mvc\Core\Providers\MiddlewareServiceProvider;
use Bibo\Mvc\Core\Providers\ModelServiceProvider;
use Bibo\Mvc\Core\Providers\RequestServiceProvider;
use Bibo\Mvc\Core\Providers\RouteServiceProvider;
use Bibo\Mvc\Core\Providers\ScriptExecutionTimerServiceProvider;
use Bibo\Mvc\Core\Providers\StartupConfigServiceProvider;
use Bibo\Mvc\Core\Providers\TemplateServiceProvider;
use Bibo\Mvc\Core\Providers\ViewServiceProvider;
use Bibo\Mvc\Core\Providers\ServiceProvider;

if (!isset($app)) {
    throw new RuntimeException('App not configured');
}

// Core service providers
$providers = [
    ScriptExecutionTimerServiceProvider::class,
    ConfigServiceProvider::class,
    StartupConfigServiceProvider::class,
    CookieServiceProvider::class,
    CookieJarServiceProvider::class,
    EnumServiceProvider::class,
    LogServiceProvider::class,
    CryptoServiceProvider::class,
    TemplateServiceProvider::class,
    ViewServiceProvider::class,
    ErrorServiceProvider::class,
    MiddlewareServiceProvider::class,
    AppServiceProvider::class,
    RouteServiceProvider::class,
    RequestServiceProvider::class,
    ModelServiceProvider::class,
    FileCacheServiceProvider::class,
    ControllerServiceProvider::class,
];

$container = $app->container();
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
