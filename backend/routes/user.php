<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::prefix('users')->name('users.')->group(function () {
    /*
    *   url: /users/
    *   name: users.
    */
    Route::controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('show/{user}', 'show')->name('show');
        Route::get('search', 'search')->name('search');
        Route::put('/', 'update')->name('update');
    });
});
