<?php

use Bibo\App\Controllers\IndexController;
use Bibo\App\Controllers\InstallController;
use Bibo\App\Controllers\TestController;
use Bibo\Mvc\Core\Facades\Route;
use Bibo\Mvc\Core\Request\BaseRequest;
use Bibo\Mvc\Core\Response\JsonResponse;

Route::get('/', [IndexController::class, 'index'])
    ->name('home');


Route::get('/api/ping', [TestController::class, 'ping'])
    ->middleware('cors')
    ->name('api.ping');

Route::get('/rest/ping', function () {
    return new JsonResponse([
        'status' => 'success',
        'message' => 'pong',
    ]);
});

Route::resource('/rest', IndexController::class);

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
    return "Route::get('/info', function () {";
})->middleware('rate_limit');

Route::get('/api/json', [IndexController::class, 'api']);

Route::get('/user/{name}', function (string $name) {
    return 'Hello ' . $name;
});

Route::get('/edit/{id}', function (int $id) {
    return 'Edit user with id: ' . $id;
});

Route::get('/user/{name}/{id:\d+}', [IndexController::class, 'user']);

Route::get('/install', [InstallController::class, 'index']);

Route::get('/test', function (BaseRequest $request) {
    dd($request);
});
