<?php

use App\Controllers\IndexController;
use Biboletin\Mvc\App;
use Biboletin\Mvc\Router;

require __DIR__ . '/../vendor/autoload.php';

$request = new \Biboletin\Mvc\Request();

echo '<pre>' . print_r($request, true) . '</pre>';
