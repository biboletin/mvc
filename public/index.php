<?php

use Bibo\Core\Base\App;
use Bibo\Core\BaseRouter\CachedRegexMatchStrategy;
use Bibo\Core\Container\Container;
use Bibo\Core\Facades\Route;
use Bibo\Core\Request\BaseRequest;
use Bibo\Core\Response\BaseResponse;
use Bibo\Core\BaseRouter\BaseRouter;

include __DIR__ . '/../vendor/autoload.php';


$router = new BaseRouter(new CachedRegexMatchStrategy());
Route::init($router);

include __DIR__ . '/../routes/web.php';


$app = new App(new Container());
$app->container()
    ->set('router', fn () => $router)
    ->set('request', fn () => new BaseRequest())
    ->set('response', fn () => new BaseResponse());

// Now, you can run the app and it will handle the routing and response.
$app->run();

// dd($app);
