<?php

use App\Application\Health\Actions\CheckSystemHealthAction;
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

// Health check (used by Docker HEALTHCHECK and monitoring)
Route::get('/health', function (CheckSystemHealthAction $healthAction) {
    $report = $healthAction->execute();
    $statusCode = $report['status'] === 'ok' ? 200 : 503;

    return response()->json($report, $statusCode);
})->name('health');

// Feature routes
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/settings.php';
