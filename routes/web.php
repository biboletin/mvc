<?php

use Bibo\Core\Facades\Route;
use Bibo\App\Controllers\IndexController;

// Route::get('/', [IndexController::class, 'index']);
//
// Route::get('/about', [IndexController::class, 'about']);
//
// Route::get('/contacts', [IndexController::class, 'contacts']);
//
// Route::get('/info', function () {
//     echo 'Info func';
// });
//
// Route::get('/json', [IndexController::class, 'json']);

// Route::get('/user/{name}', function (string $name) {
//     return 'Hello ' . $name;
// });

Route::get('/user/{\d+}', function (int $id) {
    return 'Hello user with id: ' . $id;
});

Route::get('/user/{name}/{\d+}', function (string $name, int $id) {
    return 'Hello ' . $name . ' with id: ' . $id;
});
