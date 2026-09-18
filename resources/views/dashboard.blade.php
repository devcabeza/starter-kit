@extends('layouts.app')

@section('content')
<div class="min-h-screen-safe flex flex-col pt-safe pb-safe">
    {{-- Top Navbar --}}
    <header class="border-b border-zinc-800/80 bg-zinc-900/50 backdrop-blur-xl sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('favicon.svg') }}" alt="Laravertex" class="w-8 h-8">
                <span class="font-bold text-lg tracking-tight text-white">Laravertex</span>
            </div>

            {{-- User Avatar Dropdown --}}
            <x-dropdown align="end" width="w-64">
                <x-slot:trigger>
                    <div class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-zinc-800/60 transition-colors cursor-pointer group">
                        <x-avatar :initials="$user->initials()" size="sm" />
                        <div class="text-left hidden sm:block">
                            <div class="text-sm font-medium text-white truncate max-w-[140px]">{{ $user->name }}</div>
                            <div class="text-xs text-zinc-400 truncate max-w-[140px]">{{ $user->email }}</div>
                        </div>
                        <svg class="w-4 h-4 text-zinc-400 group-hover:text-zinc-200 transition-transform duration-200 group-focus:rotate-180 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </x-slot:trigger>

                {{-- User Info Header --}}
                <x-dropdown-header>
                    <div class="font-semibold text-white truncate">{{ $user->name }}</div>
                    <div class="text-xs text-zinc-400 truncate font-normal">{{ $user->email }}</div>
                </x-dropdown-header>

                <x-dropdown-separator />

                <x-dropdown-item href="{{ route('profile') }}">
                    <x-slot:icon>
                        <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </x-slot:icon>
                    Perfil
                </x-dropdown-item>

                <x-dropdown-item href="{{ route('settings') }}">
                    <x-slot:icon>
                        <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </x-slot:icon>
                    Configuraciones
                </x-dropdown-item>

                <x-dropdown-separator />

                <x-dropdown-item :action="route('auth.logout')" variant="danger">
                    <x-slot:icon>
                        <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </x-slot:icon>
                    Cerrar sesión
                </x-dropdown-item>
            </x-dropdown>
        </div>
    </header>

    {{-- Main Content Container --}}
    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        {{-- Welcome Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-900/40 via-zinc-900 to-zinc-950 border border-indigo-500/20 p-6 sm:p-10 shadow-2xl">
            <div class="relative z-10 space-y-3 max-w-2xl">
                <x-badge variant="neutral" size="sm" class="bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 uppercase tracking-wider font-semibold py-1 px-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sesión iniciada vía Magic Link
                </x-badge>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    ¡Hola, {{ $user->name }}!
                </h2>
                <p class="text-zinc-300 text-base sm:text-lg leading-relaxed">
                    Te has autenticado correctamente sin contraseñas. Tu cuenta está activa y verificada con el correo <strong class="text-white">{{ $user->email }}</strong>.
                </p>
            </div>
        </div>

        {{-- Bento Grid Features --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Mobile & Capacitor Card --}}
            <x-card>
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Capacitor Mobile APK</h3>
                    <p class="text-sm text-zinc-400">
                        Optimizado para WebViews Android e iOS con Safe Areas, zonas de pulgar y feedback táctil de 60fps.
                    </p>
                </div>

                <x-slot:actions class="w-full">
                    <x-button
                        href="https://github.com/devcabeza/starter-kit/releases/latest/download/app-debug.apk"
                        variant="neutral"
                        class="w-full bg-zinc-800 hover:bg-zinc-700 text-white"
                        download="Laravertex.apk"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Descargar APK</span>
                    </x-button>
                </x-slot:actions>
            </x-card>

            {{-- Hexagonal Architecture Card --}}
            <x-card>
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Arquitectura Hexagonal</h3>
                    <p class="text-sm text-zinc-400">
                        Dominio desacoplado (pure PHP), Casos de Uso en Aplicación, Puertos orientados a interfaces y adaptadores Eloquent.
                    </p>
                </div>

                <div class="text-xs text-zinc-400 font-mono pt-2">
                    app/Domain | app/Application | app/Ports
                </div>
            </x-card>

            {{-- Queues & Horizon Card --}}
            <x-card>
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Segundo Plano & Horizon</h3>
                    <p class="text-sm text-zinc-400">
                        Los correos de Magic Link se envían mediante colas asíncronas para máxima velocidad y fiabilidad en producción.
                    </p>
                </div>

                <div class="text-xs text-zinc-400 font-mono pt-2">
                    Queue: emails | ShouldQueue
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
