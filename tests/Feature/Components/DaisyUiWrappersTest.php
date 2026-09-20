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

test('select component renders options, label, and attributes', function () {
    $html = Blade::render('<x-select name="role" label="Rol" :options="[\'admin\' => \'Administrador\', \'user\' => \'Usuario\']" />');

    expect($html)
        ->toContain('Rol')
        ->toContain('name="role"')
        ->toContain('select')
        ->toContain('select-bordered')
        ->toContain('value="admin"')
        ->toContain('Administrador');
});

test('textarea component renders label, rows, and slot content', function () {
    $html = Blade::render('<x-textarea name="bio" label="Biografía" rows="4">Texto de prueba</x-textarea>');

    expect($html)
        ->toContain('Biografía')
        ->toContain('name="bio"')
        ->toContain('rows="4"')
        ->toContain('textarea')
        ->toContain('textarea-bordered')
        ->toContain('Texto de prueba');
});

test('checkbox component renders with touch target and label', function () {
    $html = Blade::render('<x-checkbox name="terms" label="Acepto los términos" color="primary" />');

    expect($html)
        ->toContain('type="checkbox"')
        ->toContain('name="terms"')
        ->toContain('checkbox')
        ->toContain('checkbox-primary')
        ->toContain('min-h-[44px]')
        ->toContain('Acepto los términos');
});

test('toggle component renders switch with label and touch target', function () {
    $html = Blade::render('<x-toggle name="notifications" label="Notificaciones push" color="success" />');

    expect($html)
        ->toContain('type="checkbox"')
        ->toContain('name="notifications"')
        ->toContain('toggle')
        ->toContain('toggle-success')
        ->toContain('min-h-[44px]')
        ->toContain('Notificaciones push');
});

test('file-input component renders with label and file classes', function () {
    $html = Blade::render('<x-file-input name="document" label="Subir Archivo" />');

    expect($html)
        ->toContain('type="file"')
        ->toContain('name="document"')
        ->toContain('file-input')
        ->toContain('file-input-bordered')
        ->toContain('Subir Archivo');
});

test('skeleton component renders pulse animation placeholder', function () {
    $html = Blade::render('<x-skeleton shape="circle" class="w-12 h-12" />');

    expect($html)
        ->toContain('skeleton')
        ->toContain('rounded-full')
        ->toContain('w-12 h-12');
});

test('spinner component renders loading spinner with size and color', function () {
    $html = Blade::render('<x-spinner size="lg" color="primary" />');

    expect($html)
        ->toContain('loading')
        ->toContain('loading-spinner')
        ->toContain('loading-lg');
});

test('toast component renders toast container at specified position', function () {
    $html = Blade::render('<x-toast position="top-end"><span>Notificación</span></x-toast>');

    expect($html)
        ->toContain('toast')
        ->toContain('toast-top toast-end')
        ->toContain('Notificación');
});

test('bottom-nav component renders fixed container with safe area inset', function () {
    $html = Blade::render('<x-bottom-nav><button>Inicio</button></x-bottom-nav>');

    expect($html)
        ->toContain('btm-nav')
        ->toContain('fixed bottom-0')
        ->toContain('Inicio');
});

test('drawer component renders drawer container with sidebar slot', function () {
    $html = Blade::render('
        <x-drawer id="test-drawer">
            <p>Contenido principal</p>
            <x-slot:sidebar>
                <a>Elemento lateral</a>
            </x-slot:sidebar>
        </x-drawer>
    ');

    expect($html)
        ->toContain('drawer')
        ->toContain('drawer-toggle')
        ->toContain('drawer-content')
        ->toContain('drawer-side')
        ->toContain('Contenido principal')
        ->toContain('Elemento lateral');
});

test('honeypot component renders hidden spam trap', function () {
    $html = Blade::render('<x-honeypot name="anti_spam_trap" />');

    expect($html)
        ->toContain('display:none')
        ->toContain('anti_spam_trap')
        ->toContain('tabindex="-1"');
});

test('collapse component renders details with summary and collapse classes', function () {
    $html = Blade::render('<x-collapse title="¿Cómo funciona?">Explicación del contenido</x-collapse>');

    expect($html)
        ->toContain('<details')
        ->toContain('collapse')
        ->toContain('collapse-arrow')
        ->toContain('¿Cómo funciona?')
        ->toContain('Explicación del contenido');
});
