<?php

use App\Livewire\Profile\EditProfile;
use App\Livewire\Settings\AccountSettings;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Settings & Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', EditProfile::class)->name('profile');
    Route::get('/settings', AccountSettings::class)->name('settings');
});
