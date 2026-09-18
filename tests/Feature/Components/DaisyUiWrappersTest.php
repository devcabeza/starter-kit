<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

test('button component renders default primary button with touch target', function () {
    $html = Blade::render('<x-button>Click Me</x-button>');

    expect($html)
        ->toContain('<button')
        ->toContain('type="button"')
        ->toContain('btn')
        ->toContain('btn-primary')
        ->toContain('min-h-[44px]')
        ->toContain('Click Me');
});

test('button component supports variants, sizes, and submit type', function () {
    $html = Blade::render('<x-button variant="danger" size="lg" type="submit">Eliminar</x-button>');

    expect($html)
        ->toContain('type="submit"')
        ->toContain('btn-error')
        ->toContain('btn-lg')
        ->toContain('Eliminar');
});

test('button component renders anchor tag when href is provided', function () {
    $html = Blade::render('<x-button href="/dashboard" variant="ghost">Ir al Dashboard</x-button>');

    expect($html)
        ->toContain('<a')
        ->toContain('href="/dashboard"')
        ->toContain('btn-ghost')
        ->toContain('Ir al Dashboard');
});

test('button component renders loading spinner when loading is true', function () {
    $html = Blade::render('<x-button :loading="true">Cargando...</x-button>');

    expect($html)
        ->toContain('loading-spinner')
        ->toContain('disabled');
});

test('input component renders with label, bordered style, and attributes', function () {
    $html = Blade::render('<x-input name="email" label="Correo" placeholder="test@example.com" wire:model="email" />');

    expect($html)
        ->toContain('Correo')
        ->toContain('name="email"')
        ->toContain('input')
        ->toContain('input-bordered')
        ->toContain('wire:model="email"');
});

test('input component displays validation error when present', function () {
    $errors = new ViewErrorBag;
    $errors->put('default', new MessageBag(['email' => ['El correo es obligatorio']]));
    View::share('errors', $errors);

    $html = Blade::render('<x-input name="email" label="Correo" />');

    expect($html)
        ->toContain('input-error')
        ->toContain('El correo es obligatorio');
});

test('input component accepts explicit error prop', function () {
    $html = Blade::render('<x-input name="email" error="Error específico" />');

    expect($html)
        ->toContain('input-error')
        ->toContain('Error específico');
});

test('card component renders title, body content, and actions slot', function () {
    $html = Blade::render('
        <x-card title="Mi Tarjeta">
            <p>Contenido del cuerpo</p>
            <x-slot:actions>
                <x-button size="sm">Aceptar</x-button>
            </x-slot:actions>
        </x-card>
    ');

    expect($html)
        ->toContain('card')
        ->toContain('Mi Tarjeta')
        ->toContain('Contenido del cuerpo')
        ->toContain('card-actions')
        ->toContain('Aceptar');
});

test('badge component renders with proper variant class and content', function () {
    $html = Blade::render('<x-badge variant="success" size="sm">Completado</x-badge>');

    expect($html)
        ->toContain('badge')
        ->toContain('badge-success')
        ->toContain('badge-sm')
        ->toContain('Completado');
});

test('alert component renders with alert classes and text', function () {
    $html = Blade::render('<x-alert type="success">Operación exitosa</x-alert>');

    expect($html)
        ->toContain('alert')
        ->toContain('alert-success')
        ->toContain('Operación exitosa');
});

test('modal component renders dialog with modal class and id', function () {
    $html = Blade::render('
        <x-modal id="test-modal" title="Confirmación">
            <p>¿Estás seguro?</p>
        </x-modal>
    ');

    expect($html)
        ->toContain('<dialog id="test-modal"')
        ->toContain('modal')
        ->toContain('modal-box')
        ->toContain('Confirmación')
        ->toContain('¿Estás seguro?');
});

test('avatar component renders with placeholder initials and size', function () {
    $html = Blade::render('<x-avatar initials="AC" size="sm" />');

    expect($html)
        ->toContain('avatar')
        ->toContain('avatar-placeholder')
        ->toContain('w-9 h-9')
        ->toContain('AC');
});

test('dropdown component renders trigger, dropdown classes and content', function () {
    $html = Blade::render('
        <x-dropdown align="end">
            <x-slot:trigger>
                <span>Abrir menú</span>
            </x-slot:trigger>
            <x-dropdown-header>Mi Cuenta</x-dropdown-header>
            <x-dropdown-item href="/perfil">Perfil</x-dropdown-item>
            <x-dropdown-separator />
            <x-dropdown-item action="/logout" method="POST" variant="danger">Salir</x-dropdown-item>
        </x-dropdown>
    ');

    expect($html)
        ->toContain('dropdown')
        ->toContain('dropdown-end')
        ->toContain('dropdown-content')
        ->toContain('menu')
        ->toContain('Abrir menú')
        ->toContain('menu-title')
        ->toContain('Mi Cuenta')
        ->toContain('href="/perfil"')
        ->toContain('Perfil')
        ->toContain('action="/logout"')
        ->toContain('type="submit"')
        ->toContain('text-rose-400')
        ->toContain('Salir');
});
