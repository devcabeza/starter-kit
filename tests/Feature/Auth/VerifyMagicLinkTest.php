<?php

declare(strict_types=1);

use App\Infrastructure\Mail\MagicLinkMail;
use App\Infrastructure\Persistence\Eloquent\Models\EloquentMagicLinkToken;
use App\Livewire\Auth\VerifyToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

test('redirects to login if no email provided', function () {
    $this->get(route('auth.verify'))
        ->assertRedirect(route('auth.login'));
});

test('renders verify page when email is present', function () {
    $this->get(route('auth.verify', ['email' => 'test@example.com']))
        ->assertOk()
        ->assertSee('Revisa tu correo')
        ->assertSee('test@example.com');
});

test('successfully verifies valid 6-digit code, logs in user and redirects to dashboard', function () {
    $user = User::factory()->unverified()->create([
        'email' => 'auth.test@example.com',
    ]);

    $rawCode = '654321';
    $token = EloquentMagicLinkToken::create([
        'user_id' => $user->id,
        'email' => $user->email,
        'token_hash' => hash('sha256', $rawCode),
        'expires_at' => now()->addMinutes(15),
    ]);

    Livewire::test(VerifyToken::class, ['email' => $user->email])
        ->set('token', $rawCode)
        ->call('verify')
        ->assertRedirect(route('dashboard'));

    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($user->id);

    $token->refresh();
    expect($token->used_at)->not->toBeNull();

    $user->refresh();
    expect($user->email_verified_at)->not->toBeNull();
});

test('fails verification with invalid code', function () {
    $user = User::factory()->create([
        'email' => 'auth.test@example.com',
    ]);

    EloquentMagicLinkToken::create([
        'user_id' => $user->id,
        'email' => $user->email,
        'token_hash' => hash('sha256', '123456'),
        'expires_at' => now()->addMinutes(15),
    ]);

    Livewire::test(VerifyToken::class, ['email' => $user->email])
        ->set('token', '999999')
        ->call('verify')
        ->assertSet('errorMessage', 'El código de acceso no es válido.')
        ->assertNoRedirect();

    expect(Auth::check())->toBeFalse();
});

test('fails verification when token is expired', function () {
    $user = User::factory()->create([
        'email' => 'expired@example.com',
    ]);

    EloquentMagicLinkToken::create([
        'user_id' => $user->id,
        'email' => $user->email,
        'token_hash' => hash('sha256', '123456'),
        'expires_at' => now()->subMinute(),
    ]);

    Livewire::test(VerifyToken::class, ['email' => $user->email])
        ->set('token', '123456')
        ->call('verify')
        ->assertSet('errorMessage', 'El código de acceso no es válido.')
        ->assertNoRedirect();

    expect(Auth::check())->toBeFalse();
});

test('fails verification when token has already been used', function () {
    $user = User::factory()->create([
        'email' => 'used@example.com',
    ]);

    EloquentMagicLinkToken::create([
        'user_id' => $user->id,
        'email' => $user->email,
        'token_hash' => hash('sha256', '123456'),
        'expires_at' => now()->addMinutes(15),
        'used_at' => now()->subMinute(),
    ]);

    Livewire::test(VerifyToken::class, ['email' => $user->email])
        ->set('token', '123456')
        ->call('verify')
        ->assertSet('errorMessage', 'El código de acceso no es válido.')
        ->assertNoRedirect();

    expect(Auth::check())->toBeFalse();
});

test('auto-verifies on mount with 1-click magic link query parameter', function () {
    $user = User::factory()->create([
        'email' => 'click@example.com',
    ]);

    $rawCode = '789123';
    EloquentMagicLinkToken::create([
        'user_id' => $user->id,
        'email' => $user->email,
        'token_hash' => hash('sha256', $rawCode),
        'expires_at' => now()->addMinutes(15),
    ]);

    // Simulates opening the email link: /auth/verify?email=click@example.com&token=789123
    Livewire::withQueryParams([
        'email' => $user->email,
        'token' => $rawCode,
    ])
        ->test(VerifyToken::class)
        ->assertRedirect(route('dashboard'));

    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($user->id);
});

test('can resend code successfully and queues mail', function () {
    Mail::fake();

    $user = User::factory()->create([
        'email' => 'resend@example.com',
    ]);

    Livewire::test(VerifyToken::class, ['email' => $user->email])
        ->call('resend')
        ->assertSet('successMessage', 'Hemos enviado un nuevo código de 6 dígitos a tu correo.');

    Mail::assertQueued(MagicLinkMail::class);
});
