<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 User Routes
|--------------------------------------------------------------------------
|
| CRUD operations for users via API.
|
*/

Route::prefix('users')->name('api.v1.users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])
        ->name('index');
    Route::get('/{user}', [UserController::class, 'show'])
        ->name('show');
    Route::post('/', [UserController::class, 'store'])
        ->name('store');
    Route::put('/{user}', [UserController::class, 'update'])
        ->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->name('destroy');
});
