<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for the admin panel. Protected by auth and admin middleware.
| Prefix: /admin
|
*/

Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard.index');

    // Add admin routes here as needed:
    // Route::resource('users', UserController::class);
    // Route::resource('posts', PostController::class);
});
