<?php

declare(strict_types=1);

use App\Models\User;

test('guests are redirected from dashboard to login', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

test('authenticated user can view dashboard and see their info', function () {
    $user = User::factory()->create([
        'name' => 'Alejandro Cabeza',
        'email' => 'alejandro@example.com',
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('¡Hola, Alejandro Cabeza!')
        ->assertSee('alejandro@example.com')
        ->assertSee('Perfil')
        ->assertSee('Configuraciones')
        ->assertSee('Cerrar sesión');
});

test('authenticated user can log out via post request', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('auth.logout'))
        ->assertRedirect(route('auth.login'));

    $this->assertGuest();
});
