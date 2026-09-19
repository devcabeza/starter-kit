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
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
        $this->configureMailTransport();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

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
     * Register the Sendrix mail transport.
     */
    protected function configureMailTransport(): void
    {
        Mail::extend('sendrix', function () {
            return new SendrixTransport(
                apiKey: (string) config('services.sendrix.api_key', ''),
                projectId: (string) config('services.sendrix.project_id', ''),
                baseUrl: (string) config('services.sendrix.base_url', ''),
            );
        });
    }
}
