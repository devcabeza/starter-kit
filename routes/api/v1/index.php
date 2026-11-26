<?php

declare(strict_types=1);

use App\Application\Health\Actions\CheckSystemHealthAction;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
|
| Root file for API v1. All endpoints are versioned and rate-limited.
|
*/

Route::middleware(['throttle:api'])->group(function (): void {
    // Health check (public)
    Route::get('/health', function (CheckSystemHealthAction $healthAction) {
        $report = $healthAction->execute();
        $statusCode = $report['status'] === 'ok' ? 200 : 503;

        return ApiResponse::success(
            data: $report,
            message: $report['status'] === 'ok' ? 'System healthy' : 'System degraded',
            statusCode: $statusCode,
            meta: ['version' => 'v1'],
        );
    })->name('api.v1.health');

    // Authenticated routes via Sanctum
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/user', function (Request $request) {
            return ApiResponse::success(
                data: $request->user(),
                message: 'Perfil de usuario obtenido con éxito.',
            );
        })->name('api.v1.user');
    });
});
