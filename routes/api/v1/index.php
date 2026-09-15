<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
|
| Root file for API v1. Load feature route files here.
|
*/

// Health check (public)
Route::get('/health', fn () => response()->json([
    'status' => 'ok',
    'version' => 'v1',
]))->name('api.v1.health');

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    require __DIR__.'/users.php';
});
