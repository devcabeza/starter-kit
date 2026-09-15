<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Routes for login, register, password reset, and other auth flows.
| Uses Laravel Breeze/Fortify patterns|
*/

// Guest routes (redirect if already authenticated)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Register
    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    // Password Reset
    Route::get('/forgot-password', [PasswordController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'reset'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'update'])
        ->name('password.update');

    // Email Verification
    Route::get('/verify-email', [EmailVerificationController::class, 'show'])
        ->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});
