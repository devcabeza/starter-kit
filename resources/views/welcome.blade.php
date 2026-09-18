<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <title>Laravertex</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-zinc-950 text-zinc-100 antialiased font-sans touch-callout-none overflow-x-hidden selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen-safe w-full flex flex-col justify-between pt-safe pb-safe px-4 sm:px-6 lg:px-8">
            {{-- Top Header / Safe Area Protected --}}
            <header class="w-full py-4 flex items-center justify-between max-w-5xl mx-auto">
                <div class="flex items-center gap-2 select-none">
                    <img src="{{ asset('favicon.svg') }}" alt="Laravertex" class="w-8 h-8 pointer-events-none select-none">
                    <span class="font-bold text-lg tracking-tight text-white">Laravertex</span>
                </div>
                <div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="min-h-[44px] inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-200 hover:text-white active:scale-95 transition-all select-none">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('auth.login') }}" class="min-h-[44px] inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl bg-indigo-600/10 border border-indigo-500/30 text-indigo-300 hover:text-indigo-200 active:scale-95 transition-all select-none">
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </header>

            {{-- Center Hero Content --}}
            <main class="flex-1 flex flex-col items-center justify-center text-center py-8 sm:py-12 max-w-xl mx-auto w-full space-y-6">
                {{-- Glowing Logo Container --}}
                <div class="relative select-none">
                    <div class="absolute -inset-4 bg-indigo-500/20 rounded-3xl blur-xl"></div>
                    <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-zinc-900 border border-zinc-800/80 p-4 shadow-2xl flex items-center justify-center">
                        <img src="{{ asset('favicon.svg') }}" alt="Laravertex" class="w-full h-full object-contain pointer-events-none">
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs font-medium select-none">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    <span>Capacitor Mobile APK & Web Starter Kit</span>
                </div>

                {{-- Title & Subtitle --}}
                <div class="space-y-3 select-text">
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white">
                        Laravertex
                    </h1>
                    <p class="text-base sm:text-lg text-zinc-400 leading-relaxed max-w-md mx-auto">
                        Crea y prueba rápido, distribuye en APK nativo y web, escala de inmediato.
                    </p>
                </div>

                {{-- Thumb Zone Action Buttons (Full width on mobile < sm, inline on sm+) --}}
                <div class="w-full flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="w-full sm:w-auto min-h-[48px] px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-base shadow-lg shadow-indigo-600/25 active:scale-95 transition-all duration-150 inline-flex items-center justify-center gap-2 select-none">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span>Ir al Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('auth.login') }}"
                           class="w-full sm:w-auto min-h-[48px] px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-base shadow-lg shadow-indigo-600/25 active:scale-95 transition-all duration-150 inline-flex items-center justify-center gap-2 select-none">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span>Iniciar Sesión / Registro</span>
                        </a>
                    @endauth

                    <a href="https://github.com/devcabeza/starter-kit/releases/latest/download/app-debug.apk"
                       download="Laravertex.apk"
                       class="w-full sm:w-auto min-h-[48px] px-6 py-3 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-zinc-100 font-semibold text-base border border-zinc-700/80 active:scale-95 transition-all duration-150 inline-flex items-center justify-center gap-2 select-none shadow-sm">
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Descargar APK</span>
                    </a>
                </div>
            </main>

            {{-- Bottom Footer with Safe Area --}}
            <footer class="w-full py-4 text-center select-none">
                <p class="text-xs text-zinc-400">
                    Laravertex Starter Kit &copy; {{ date('Y') }} &bull; Optimizado para Capacitor APK & Web
                </p>
            </footer>
        </div>
    </body>
</html>
