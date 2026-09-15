<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
|
| Routes for user settings: profile, password, notifications, etc.
| Protected by auth middleware.
|
*/

Route::middleware(['auth', 'verified'])->prefix('settings')->name('settings.')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Password
    Route::get('/password', [PasswordController::class, 'edit'])
        ->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])
        ->name('password.update');
});
