<?php

declare(strict_types=1);

use App\Livewire\Settings\AccountSettings;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected from settings to login', function () {
    $this->get(route('settings'))
        ->assertRedirect(route('login'));
});

test('authenticated user can view settings page', function () {
    $user = User::factory()->create([
        'name' => 'María López',
        'email' => 'maria@example.com',
    ]);

    $this->actingAs($user);

    Livewire::test(AccountSettings::class)
        ->assertOk()
        ->assertSee('Configuraciones')
        ->assertSee('Seguridad y Autenticación')
        ->assertSee('Zona de Peligro')
        ->assertSee('maria@example.com');
});

test('authenticated user can delete their account and is logged out', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(AccountSettings::class)
        ->call('deleteAccount')
        ->assertRedirect(route('home'));

    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
