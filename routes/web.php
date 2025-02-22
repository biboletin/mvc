<?php

use Bibo\Core\Facades\Route;
use Bibo\Core\Response\JsonResponse;

// Route::get('/', function () {
//     return new JsonResponse(['message' => 'Welcome to Home']);
// });

Route::get('/', function () {
    echo 'Hello';
});

// Route::get('/', function () {
//     return 'Hello';
// });

// Route::get('/{name}', function ($name) {
//     return 'Hello ' . $name;
// });

// Route::get('/', function () {
//     return view('index');
// });

// Route::get('/', [Controller::class, 'index']);
