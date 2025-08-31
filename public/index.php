<?php

use Bibo\Mvc\Core\Base\App;
use Bibo\Mvc\Core\Base\Kernel;
use Bibo\Mvc\Core\Container\Container;
use Bibo\Mvc\Core\Exception\Custom\Http\ResponseExceptionHandler;
use Bibo\Mvc\Core\ScriptExecutionTimer\ScriptExecutionTimer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

include __DIR__ . '/../vendor/autoload.php';

define('APP_START', microtime(true));

$timer = new ScriptExecutionTimer();
$timer->start('total_execution_time');

$app = new App(new Container());


include __DIR__ . '/../bootstrap/bootstrap.php';

/*
 * Load the application configuration
 */

$kernel = new Kernel();
try {
    $kernel->bootstrap($app);
} catch (NotFoundExceptionInterface | ContainerExceptionInterface | JsonException $e) {
    ResponseExceptionHandler::handle($e);
}

$timer->stop('total_execution_time');
$timer->sendHeader();

// Now, you can run the app and it will handle the routing and response.
try {
    $app->run();
} catch (JsonException | Throwable $e) {
    ResponseExceptionHandler::handle($e);
}
