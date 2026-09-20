<?php

declare(strict_types=1);

namespace App\Application\Health\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Throwable;

final class CheckSystemHealthAction
{
    /**
     * Perform deep health checks on system dependencies.
     *
     * @return array{
     *     status: 'ok'|'error',
     *     timestamp: string,
     *     services: array<string, array{status: string, message?: string}>
     * }
     */
    public function execute(): array
    {
        $services = [];
        $healthy = true;

        // 1. Database Check
        try {
            DB::connection()->getPdo();
            $services['database'] = ['status' => 'ok'];
        } catch (Throwable $e) {
            $healthy = false;
            $services['database'] = [
                'status' => 'error',
                'message' => 'Database unreachable: '.$e->getMessage(),
            ];
        }

        // 2. Redis Check
        $usesRedis = config('cache.default') === 'redis'
            || config('queue.default') === 'redis'
            || config('session.driver') === 'redis';

        if ($usesRedis) {
            try {
                Redis::connection()->ping();
                $services['redis'] = ['status' => 'ok'];
            } catch (Throwable $e) {
                $healthy = false;
                $services['redis'] = [
                    'status' => 'error',
                    'message' => 'Redis unreachable: '.$e->getMessage(),
                ];
            }
        } else {
            $services['redis'] = ['status' => 'skipped'];
        }

        // 3. Storage Writable Check
        $storagePath = storage_path('framework/cache');
        if (is_dir($storagePath) && is_writable($storagePath)) {
            $services['storage'] = ['status' => 'ok'];
        } else {
            $healthy = false;
            $services['storage'] = [
                'status' => 'error',
                'message' => 'Storage directory is not writable: '.$storagePath,
            ];
        }

        return [
            'status' => $healthy ? 'ok' : 'error',
            'timestamp' => now()->toIso8601String(),
            'services' => $services,
        ];
    }
}
