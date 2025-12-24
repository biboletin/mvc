<?php

use Bibo\Mvc\Core\Providers\CookieJarServiceProvider;
use Bibo\Mvc\Core\Providers\CookieServiceProvider;
use Bibo\Mvc\Core\Providers\DatabaseServiceProvider;
use Bibo\Mvc\Core\Providers\EnumServiceProvider;
use Bibo\Mvc\Core\Providers\ConfigServiceProvider;
use Bibo\Mvc\Core\Providers\ControllerServiceProvider;
use Bibo\Mvc\Core\Providers\CryptoServiceProvider;
use Bibo\Mvc\Core\Providers\ErrorServiceProvider;
use Bibo\Mvc\Core\Providers\FileCacheServiceProvider;
use Bibo\Mvc\Core\Providers\LogServiceProvider;
use Bibo\Mvc\Core\Providers\MiddlewareServiceProvider;
use Bibo\Mvc\Core\Providers\ModelServiceProvider;
use Bibo\Mvc\Core\Providers\RequestServiceProvider;
use Bibo\Mvc\Core\Providers\ResolverServiceProvider;
use Bibo\Mvc\Core\Providers\ResponseEmitterServiceProvider;
use Bibo\Mvc\Core\Providers\RouteServiceProvider;
use Bibo\Mvc\Core\Providers\ScriptExecutionTimerServiceProvider;
use Bibo\Mvc\Core\Providers\SessionServiceProvider;
use Bibo\Mvc\Core\Providers\StartupServiceProvider;
use Bibo\Mvc\Core\Providers\TemplateServiceProvider;
use Bibo\Mvc\Core\Providers\ViewServiceProvider;

// Core service providers
return [
    ScriptExecutionTimerServiceProvider::class,
    RequestServiceProvider::class,
    ConfigServiceProvider::class,
    LogServiceProvider::class,
    DatabaseServiceProvider::class,
    StartupServiceProvider::class,
    ResolverServiceProvider::class,
    CookieServiceProvider::class,
    CookieJarServiceProvider::class,
    EnumServiceProvider::class,
    CryptoServiceProvider::class,
    SessionServiceProvider::class,
    TemplateServiceProvider::class,
    ViewServiceProvider::class,
    ResponseEmitterServiceProvider::class,
    ErrorServiceProvider::class,
    MiddlewareServiceProvider::class,
    FileCacheServiceProvider::class,
    RouteServiceProvider::class,
    ModelServiceProvider::class,
    ControllerServiceProvider::class,
];
