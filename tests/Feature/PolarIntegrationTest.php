<?php

declare(strict_types=1);

use App\Listeners\PolarEventListener;
use App\Models\User;
use Danestves\LaravelPolar\Billable;
use Danestves\LaravelPolar\Events\WebhookHandled;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;

test('user model uses billable trait from laravel polar', function () {
    expect(class_uses_recursive(User::class))->toContain(Billable::class);

    $user = User::factory()->create();

    expect($user->orders)->toBeEmpty()
        ->and($user->subscriptions)->toBeEmpty()
        ->and($user->subscribed())->toBeFalse();
});

test('polar configuration is loaded properly', function () {
    expect(config('polar.path'))->toBe('polar')
        ->and(config('polar.server'))->toBe('sandbox')
        ->and(config('polar.currency_locale'))->toBe('en');
});

test('polar webhook route is registered and exempt from csrf protection', function () {
    expect(Route::has('polar.webhook-client-polar'))->toBeTrue();

    // Calling the webhook without a CSRF token should NOT return 419 (CSRF token mismatch)
    $response = $this->postJson('/polar/webhook', []);

    expect($response->status())->not->toBe(419);
});

test('polar event listener handles webhook handled event', function () {
    Event::fake([WebhookHandled::class]);

    Event::assertListening(
        WebhookHandled::class,
        PolarEventListener::class,
    );
});
