<?php

use Bibo\App\Controllers\IndexController;
use Bibo\App\Controllers\InstallController;
use Bibo\Core\Facades\Route;

Route::get('/', [IndexController::class, 'index']);

Route::get('/about', [IndexController::class, 'about']);

Route::get('/contacts', [IndexController::class, 'contacts']);

Route::get('/info', function () {
    echo 'Info func';
});

Route::get('/json', [IndexController::class, 'json']);

Route::get('/user/{name}', function (string $name) {
    return 'Hello ' . $name;
});

Route::get('/edit/{id}', function (int $id) {
    return 'Hello user with id: ' . $id;
});

Route::get('/user/{name}/{id}', [IndexController::class, 'user']);

Route::get('/install', [InstallController::class, 'index']);
