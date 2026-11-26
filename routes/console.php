<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Record metrics snapshots for Laravel Horizon every 5 minutes
Schedule::command('horizon:snapshot')->everyFiveMinutes();

// Prune old Telescope entries to keep database size healthy
Schedule::command('telescope:prune --hours=48')->daily();
