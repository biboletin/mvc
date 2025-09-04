<?php

use Bibo\App\Controllers\IndexController;
use Bibo\App\Controllers\InstallController;
use Bibo\App\Controllers\TestController;
use Bibo\Mvc\Core\Facades\Route;
use Bibo\Mvc\Core\Response\JsonResponse;

Route::get('/', [IndexController::class, 'index'])
    ->middleware(['csrf', 'cors'])
    ->name('home');


Route::get('/api/ping', [TestController::class, 'ping'], ['cors'])->name('api.ping');

// Route::get('/api/ping', function () {
//     return new JsonResponse([
//         'status' => 'success',
//         'message' => 'pong',
//     ]);
// }, ['cors']);

Route::group('admin', function () {
    Route::get('/', [IndexController::class, 'index']);

    Route::get('/api/ping', function () {
        return new JsonResponse([
            'status' => 'success',
            'message' => 'pong',
        ]);
    });
})->middleware(['cors']);


Route::get('/about', [IndexController::class, 'about'])
    ->name('about')
    ->middleware('cors');

Route::get('/contacts', [IndexController::class, 'contacts']);

Route::get('/info', function () {
    echo 'Info func';
});

Route::get('/json', [IndexController::class, 'json']);

Route::get('/user/{name}', function (string $name) {
    return 'Hello ' . $name;
});

Route::get('/edit/{id}', function (int $id) {
    return 'Edit user with id: ' . $id;
});

Route::get('/user/{name}/{id}', [IndexController::class, 'user']);

Route::get('/install', [InstallController::class, 'index']);

// Route::dump();
