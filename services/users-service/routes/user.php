<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->middleware(['auth:api'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('authorizedUserData');
        Route::get('show/{user}', 'show')->name('getUserProfile');
        Route::put('/', 'update')->name('updateUserData');
    });
    Route::controller(UserSubscriptionController::class)->group(function () {
        Route::get('subscribers/{user}', 'subscribers')->name('userSubscribers');
        Route::get('subscriptions/{user}', 'subscriptions')->name('userSubscriptions');

        Route::middleware(['prevent.self.action'])->group(function () {
            Route::post('subscribe/{user}', 'subscribe')->name('subscribeOnUser');
            Route::delete('unsubscribe/{user}', 'unsubscribe')->name('unsubscribeFromUser');
        });
    });
});
