<?php

use App\Livewire\Auth\MagicLogin;
use App\Livewire\Auth\VerifyToken;
use App\Ports\In\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Passwordless Magic Link)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/auth', MagicLogin::class)->name('auth.login');
    Route::get('/login', MagicLogin::class)->name('login');
    Route::get('/auth/verify', VerifyToken::class)->name('auth.verify');
});

Route::post('/auth/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('auth.logout');
