<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', function (?User $user = null): bool {
            $allowedEmails = config('horizon.allowed_emails');

            if (is_string($allowedEmails)) {
                $allowedEmails = array_filter(array_map('trim', explode(',', $allowedEmails)));
            }

            if (! is_array($allowedEmails) || empty($allowedEmails)) {
                return false;
            }

            return in_array($user?->email, $allowedEmails, true);
        });
    }
}
