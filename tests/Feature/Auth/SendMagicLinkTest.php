<?php

declare(strict_types=1);

use App\Infrastructure\Mail\MagicLinkMail;
use App\Infrastructure\Persistence\Eloquent\Models\EloquentMagicLinkToken;
use App\Livewire\Auth\MagicLogin;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

test('renders the magic link login page correctly', function () {
    $this->get(route('auth.login'))
        ->assertOk()
        ->assertSee('Acceso sin contraseña')
        ->assertSee('Correo electrónico');
});

test('validates email is required and valid format', function () {
    Livewire::test(MagicLogin::class)
        ->set('email', '')
        ->call('submit')
        ->assertHasErrors(['email' => 'required']);

    Livewire::test(MagicLogin::class)
        ->set('email', 'not-an-email')
        ->call('submit')
        ->assertHasErrors(['email' => 'email']);
});

test('creates new user and sends magic link email when user does not exist', function () {
    Mail::fake();

    $email = 'nuevo.usuario@example.com';

    Livewire::test(MagicLogin::class)
        ->set('email', $email)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('auth.verify', ['email' => $email]));

    $this->assertDatabaseHas('users', [
        'email' => $email,
        'name' => 'Nuevo Usuario',
    ]);

    $this->assertDatabaseHas('magic_link_tokens', [
        'email' => $email,
    ]);

    Mail::assertQueued(MagicLinkMail::class, function (MagicLinkMail $mail) use ($email) {
        return $mail->hasTo($email)
            && strlen($mail->tokenCode) === 6
            && is_numeric($mail->tokenCode)
            && str_contains($mail->verificationUrl, 'auth/verify');
    });
});

test('uses existing user and invalidates previous active tokens', function () {
    Mail::fake();

    $user = User::factory()->create([
        'email' => 'existente@example.com',
        'name' => 'Usuario Existente',
    ]);

    // Create an existing active token
    $oldToken = EloquentMagicLinkToken::create([
        'user_id' => $user->id,
        'email' => $user->email,
        'token_hash' => hash('sha256', '111111'),
        'expires_at' => now()->addMinutes(15),
    ]);

    Livewire::test(MagicLogin::class)
        ->set('email', $user->email)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('auth.verify', ['email' => $user->email]));

    // Old token should be invalidated (expires_at <= now())
    $oldToken->refresh();
    expect($oldToken->expires_at->isPast())->toBeTrue();

    // Exactly one active token should exist now
    $activeCount = EloquentMagicLinkToken::where('email', $user->email)
        ->where('expires_at', '>', now())
        ->count();

    expect($activeCount)->toBe(1);

    Mail::assertQueued(MagicLinkMail::class);
});
