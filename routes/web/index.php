<?php

use Illuminate\Support\Facades\DB;
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

// Health check (used by Docker HEALTHCHECK)
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();

        return response()->json(['status' => 'ok'], 200);
    } catch (Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Database unreachable'], 503);
    }
})->name('health');

// Feature routes
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
