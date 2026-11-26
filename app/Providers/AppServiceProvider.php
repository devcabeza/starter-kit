<?php

namespace App\Providers;

use App\Infrastructure\Messaging\MagicLinkNotifier;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentMagicLinkTokenRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository;
use App\Mail\SendrixTransport;
use App\Ports\Out\Messaging\MagicLinkNotifierInterface;
use App\Ports\Out\Persistence\MagicLinkTokenRepositoryInterface;
use App\Ports\Out\Persistence\UserRepositoryInterface;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class,
        );

        $this->app->bind(
            MagicLinkTokenRepositoryInterface::class,
            EloquentMagicLinkTokenRepository::class,
        );

        $this->app->bind(
            MagicLinkNotifierInterface::class,
            MagicLinkNotifier::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();
        $this->configureMailTransport();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        Model::shouldBeStrict(
            ! $this->app->isProduction(),
        );

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Configure rate limiting for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: (string) $request->ip());
        });
    }

    /**
     * Register the Sendrix mail transport.
     */
    protected function configureMailTransport(): void
    {
        Mail::extend('sendrix', function () {
            return new SendrixTransport(
                key: (string) config('services.sendrix.key', config('services.sendrix.api_key', '')),
                baseUrl: (string) config('services.sendrix.base_url', 'https://sendrix.alejandrocabeza.dev'),
            );
        });
    }
}
