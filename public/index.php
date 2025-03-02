<?php

use Bibo\Core\Base\App;
use Bibo\Core\Base\Kernel;
use Bibo\Core\Container\Container;
use Bibo\Core\Exception\ResponseExceptionHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

include __DIR__ . '/../vendor/autoload.php';


$app = new App(new Container());


include __DIR__ . '/../bootstrap/bootstrap.php';



$kernel = new Kernel();
try {
    $kernel->bootstrap($app);
} catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
}

// Now, you can run the app and it will handle the routing and response.
try {
    $app->run();
} catch (Throwable $e) {
    ResponseExceptionHandler::handle($e);
}

// dd($app);
