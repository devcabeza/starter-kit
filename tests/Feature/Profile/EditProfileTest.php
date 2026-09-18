<?php

declare(strict_types=1);

use App\Livewire\Profile\EditProfile;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected from profile to login', function () {
    $this->get(route('profile'))
        ->assertRedirect(route('login'));
});

test('authenticated user can view profile with prefilled data', function () {
    $user = User::factory()->create([
        'name' => 'Carlos Delgado',
        'email' => 'carlos@example.com',
    ]);

    $this->actingAs($user);

    Livewire::test(EditProfile::class)
        ->assertOk()
        ->assertSet('name', 'Carlos Delgado')
        ->assertSet('email', 'carlos@example.com')
        ->assertSee('Mi Perfil')
        ->assertSee('Información Personal');
});

test('validates name and email are required', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(EditProfile::class)
        ->set('name', '')
        ->set('email', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required', 'email' => 'required']);
});

test('validates email must be a valid email format', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(EditProfile::class)
        ->set('name', 'Nombre Válido')
        ->set('email', 'not-an-email')
        ->call('save')
        ->assertHasErrors(['email' => 'email']);
});

test('authenticated user can update their name and email', function () {
    $user = User::factory()->create([
        'name' => 'Nombre Original',
        'email' => 'original@example.com',
    ]);

    $this->actingAs($user);

    Livewire::test(EditProfile::class)
        ->set('name', 'Nombre Actualizado')
        ->set('email', 'actualizado@example.com')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('saved', true);

    $user->refresh();
    expect($user->name)->toBe('Nombre Actualizado')
        ->and($user->email)->toBe('actualizado@example.com');
});

test('cannot update email to one already registered by another user', function () {
    User::factory()->create(['email' => 'otro@example.com']);
    $currentUser = User::factory()->create(['email' => 'yo@example.com']);

    $this->actingAs($currentUser);

    Livewire::test(EditProfile::class)
        ->set('email', 'otro@example.com')
        ->call('save')
        ->assertHasErrors(['email']);
});
