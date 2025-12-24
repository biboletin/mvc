<?php

use Bibo\Mvc\Core\Application\App;
use Bibo\Mvc\Core\Application\HttpKernel;
use Bibo\Mvc\Core\Container\Container;
use Bibo\Mvc\Core\Request\BaseRequest;
use Bibo\Mvc\Core\Response\ResponseEmitter;

include __DIR__ . '/../vendor/autoload.php';

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//ini_set('log_errors', '1');
//error_reporting(E_ALL);


define('APP_START', microtime(true));

//$timer = new ScriptExecutionTimer();
//$timer->start('total_execution_time');

$container = new Container();
$app = new App($container);

try {
    $app->bootstrap();
} catch (\Bibo\Mvc\Core\Exception\Custom\Container\ContainerException $e) {
    dd($e);
}

try {
    $app->boot();
} catch (\Psr\Container\NotFoundExceptionInterface | \Psr\Container\ContainerExceptionInterface $e) {
    dd($e);
}

try {
    $request = $container->get(BaseRequest::class);
} catch (\Psr\Container\ContainerExceptionInterface | \Psr\Container\NotFoundExceptionInterface | ReflectionException $e) {
    dd($e);
}

$kernel = new HttpKernel($app);

try {
    $response = $kernel->handle($request);
} catch (\Psr\Container\ContainerExceptionInterface | \Psr\Container\NotFoundExceptionInterface | ReflectionException $e) {
    dd($e);
}

try {
    $app->container()->get(ResponseEmitter::class)->emit($response);
} catch (\Psr\Container\NotFoundExceptionInterface | \Psr\Container\ContainerExceptionInterface $e) {
    dd($e);
}
//dd($response);
$kernel->terminate($request, $response);
