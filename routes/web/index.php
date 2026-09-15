<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Main entry point for web routes. Feature routes are loaded from
| separate files for better organization.
|
*/

// Public routes
Route::view('/', 'welcome')->name('home');

// Feature routes
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
