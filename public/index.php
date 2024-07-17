<?php

use App\Controllers\IndexController;
use Biboletin\Mvc\Benchmark;
use Biboletin\Mvc\Core\DIContainer\Container;
use Biboletin\Mvc\Core\Registry;
use Biboletin\Mvc\Request;
use Biboletin\Mvc\Router;

require __DIR__ . '/../vendor/autoload.php';
const APP_DEBUG = true;
if (APP_DEBUG === true) {
    $benchmark = new Benchmark();
    $benchmark->start();
}



if (defined('APP_INIT') === false) {
    /*
     * Define the application start time
     */

    define('APP_INIT', microtime(true));
}

if (defined('APP_ROOT') === false) {
    /*
     * Define the application root directory
     */

    define('APP_ROOT', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);
}

if (defined('APP_MEMORY_USAGE') === false) {
    /*
     * Define the application memory usage
     */

    define('APP_MEMORY_USAGE', memory_get_usage());
}


$router = new Router();
$router->get('/', [IndexController::class, 'index']);
$router->get('/about', [IndexController::class, 'about']);
$router->get('/contacts', [IndexController::class, 'contacts']);

//$router->get('/user/{id}', [IndexController::class, 'edit']);

$request = new Request($router);
dd($router);
$request->resolve();
//$container = new Container();
//$container->set('class', function () {
//    $object = new StdClass();
//    $object->name = 'object';
//    return $object;
//});
//
//$obj = $container->get('class');
//$obj->id = 5;
//dd($obj);
//$router->get('/', [IndexController::class, 'index']);
//$router->get('/about', [IndexController::class, 'about']);
//$router->get('/user/edit/123', [IndexController::class, 'edit']);
//$router->group('/user', function ($router) {
//    $router->get('/add/123', [IndexController::class, 'add']);
//    $router->get('/edit/123', [IndexController::class, 'edit']);
//    $router->get('/delete/123', [IndexController::class, 'delete']);
//
//});

//$kernel = new \Biboletin\Mvc\Core\Kernel();
//
//$kernel->handle($req);
//dd($router);
//dd(APP_ROOT);

if (APP_DEBUG === true) {
    $benchmark->stop();
    dd($benchmark->result());
}
