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

            <div class="flex items-center gap-4">
                {{-- User Avatar & Info (Desktop) --}}
                <div class="hidden sm:flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-300 font-semibold text-sm">
                        {{ $user->initials() }}
                    </div>
                    <div class="text-left">
                        <div class="text-sm font-medium text-white truncate max-w-[140px]">{{ $user->name }}</div>
                        <div class="text-xs text-zinc-400 truncate max-w-[140px]">{{ $user->email }}</div>
                    </div>
                </div>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="min-h-[44px] px-4 py-2 text-sm font-medium rounded-xl bg-zinc-800 hover:bg-zinc-700 active:scale-95 text-zinc-200 transition-all flex items-center gap-2 cursor-pointer border border-zinc-700/60"
                    >
                        <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Main Content Container --}}
    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        {{-- Welcome Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-900/40 via-zinc-900 to-zinc-950 border border-indigo-500/20 p-6 sm:p-10 shadow-2xl">
            <div class="relative z-10 space-y-3 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sesión iniciada vía Magic Link
                </div>
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
            <div class="bg-zinc-900/70 border border-zinc-800 rounded-2xl p-6 flex flex-col justify-between space-y-4 hover:border-zinc-700 transition-colors">
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
                <div>
                    <a
                        href="https://github.com/devcabeza/starter-kit/releases/latest/download/app-debug.apk"
                        download="Laravertex.apk"
                        class="min-h-[44px] w-full px-4 py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-white text-sm font-semibold flex items-center justify-center gap-2 transition-all active:scale-95"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Descargar APK
                    </a>
                </div>
            </div>

            {{-- Hexagonal Architecture Card --}}
            <div class="bg-zinc-900/70 border border-zinc-800 rounded-2xl p-6 flex flex-col justify-between space-y-4 hover:border-zinc-700 transition-colors">
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
                <div class="text-xs text-zinc-400 font-mono">
                    app/Domain | app/Application | app/Ports
                </div>
            </div>

            {{-- Queues & Horizon Card --}}
            <div class="bg-zinc-900/70 border border-zinc-800 rounded-2xl p-6 flex flex-col justify-between space-y-4 hover:border-zinc-700 transition-colors">
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
                <div class="text-xs text-zinc-400 font-mono">
                    Queue: emails | ShouldQueue
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
