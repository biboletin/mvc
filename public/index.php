<?php

use Bibo\Core\Base\App;
use Bibo\Core\BaseRouter\BaseRouter;
use Bibo\Core\BaseRouter\CachedRegexMatchStrategy;
use Bibo\Core\Cache\FileCache;
use Bibo\Core\Container\Container;
use Bibo\Core\Facades\Route;
use Bibo\Core\Request\BaseRequest;
use Bibo\Core\Response\BaseResponse;
use Bibo\Core\Error\Error;

include __DIR__ . '/../vendor/autoload.php';

Error::register();

$router = new BaseRouter(new CachedRegexMatchStrategy());
Route::init($router);

include __DIR__ . '/../routes/web.php';


$app = new App(new Container());
$app->container()
    ->set('router', fn () => $router)
    ->set('request', fn () => new BaseRequest())
    ->set('response', fn () => new BaseResponse())
    ->set('cache', function () {
        return new FileCache(CACHE_PATH);
    })
    ->set('config', function () {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    })->get('config');

// Now, you can run the app and it will handle the routing and response.
$app->run();

// dd($app);
