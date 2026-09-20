<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('unauthorized users cannot access horizon gate', function () {
    config()->set('horizon.allowed_emails', ['admin@example.com']);

    $user = User::factory()->make(['email' => 'regular@example.com']);

    expect(Gate::forUser($user)->allows('viewHorizon'))->toBeFalse();
});

test('authorized emails can access horizon gate', function () {
    config()->set('horizon.allowed_emails', ['admin@example.com']);

    $admin = User::factory()->make(['email' => 'admin@example.com']);

    expect(Gate::forUser($admin)->allows('viewHorizon'))->toBeTrue();
});
