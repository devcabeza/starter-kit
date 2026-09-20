<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <title>Laravertex — Starter Kit Laravel 13, Livewire 4 & Capacitor APK</title>
        <meta name="description" content="Starter kit de alto rendimiento para Laravel 13, Livewire 4, DaisyUI 5, Tailwind CSS v4, Capacitor 7 y Arquitectura Hexagonal. Listo para producción y compilación de APK móvil nativo.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-950 text-zinc-100 antialiased font-sans touch-callout-none overflow-x-hidden selection:bg-indigo-500 selection:text-white">
        {{-- Ambient Lighting Background Effects --}}
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[720px] sm:w-[980px] h-[480px] bg-indigo-600/12 blur-[140px] rounded-full"></div>
            <div class="absolute top-[40%] -left-32 w-[500px] h-[500px] bg-purple-600/10 blur-[130px] rounded-full"></div>
            <div class="absolute bottom-10 right-0 w-[450px] h-[450px] bg-indigo-500/10 blur-[120px] rounded-full"></div>
        </div>

        <div class="min-h-screen-safe w-full flex flex-col justify-between pt-safe pb-safe">
            {{-- Top Sticky / Safe Area Protected Header --}}
            <header class="w-full sticky top-0 z-40 bg-zinc-950/80 backdrop-blur-xl border-b border-zinc-800/60 transition-colors">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
                    {{-- Brand Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-3 select-none group">
                        <div class="w-10 h-10 rounded-2xl bg-zinc-900 border border-zinc-800 p-2 flex items-center justify-center group-hover:border-indigo-500/50 transition-colors shadow-sm">
                            <img src="{{ asset('favicon.svg') }}" alt="Laravertex" class="w-full h-full object-contain pointer-events-none">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-xl tracking-tight text-white group-hover:text-indigo-300 transition-colors">Laravertex</span>
                            <x-badge variant="neutral" size="xs" class="border border-zinc-800 text-zinc-400 hidden sm:inline-flex">
                                v1.0
                            </x-badge>
                        </div>
                    </a>

                    {{-- Navigation Links (Desktop) --}}
                    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-zinc-400 select-none">
                        <a href="#features" class="hover:text-white transition-colors">Ventajas</a>
                        <a href="#architecture" class="hover:text-white transition-colors">Arquitectura</a>
                        <a href="#stack" class="hover:text-white transition-colors">Tech Stack</a>
                        <a href="#quickstart" class="hover:text-white transition-colors">Inicio Rápido</a>
                        <a href="#faq" class="hover:text-white transition-colors">FAQ</a>
                    </nav>

                    {{-- Header CTAs --}}
                    <div class="flex items-center gap-2 sm:gap-3">
                        <x-button
                            href="https://github.com/devcabeza/starter-kit"
                            target="_blank"
                            rel="noopener noreferrer"
                            variant="ghost"
                            size="sm"
                            class="border border-zinc-800 hover:border-zinc-700 text-zinc-300"
                        >
                            <svg class="w-4 h-4 fill-current text-white shrink-0" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                            </svg>
                            <span class="hidden sm:inline">GitHub</span>
                        </x-button>

                        <x-button
                            href="https://github.com/devcabeza/starter-kit/releases/latest/download/app-debug.apk"
                            download="Laravertex.apk"
                            variant="ghost"
                            size="sm"
                            class="hidden sm:inline-flex border border-zinc-800 hover:border-zinc-700 text-zinc-300"
                        >
                            <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>APK Móvil</span>
                        </x-button>

                        @auth
                            <x-button href="{{ route('dashboard') }}" variant="primary" size="sm" class="shadow-md shadow-indigo-600/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                                <span>Dashboard</span>
                            </x-button>
                        @else
                            <x-button href="{{ route('auth.login') }}" variant="primary" size="sm" class="shadow-md shadow-indigo-600/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <span>Iniciar Sesión</span>
                            </x-button>
                        @endauth
                    </div>
                </div>
            </header>

            {{-- Main Landing Content --}}
            <main class="flex-1 w-full flex flex-col items-center">
                {{-- 1. HERO SECTION --}}
                <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-20 pb-16 text-center">
                    <div class="max-w-4xl mx-auto flex flex-col items-center space-y-6">
                        {{-- Product Category Badge --}}
                        <div class="inline-flex items-center gap-2">
                            <x-badge variant="neutral" size="lg" class="border border-indigo-500/30 bg-indigo-950/40 text-indigo-300 py-1.5 px-4 shadow-inner">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>Laravel 13 &bull; Livewire 4 &bull; Capacitor 7 &bull; DaisyUI 5</span>
                            </x-badge>
                        </div>

                        {{-- Main H1: Outcome-Driven Headline --}}
                        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.1] select-text">
                            Construye tu SaaS en Laravel y lánzalo en
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-300 to-indigo-200">
                                Web y APK Nativo
                            </span>
                            en tiempo récord.
                        </h1>

                        {{-- H2 / Subtitle --}}
                        <p class="text-base sm:text-xl text-zinc-400 max-w-2xl mx-auto leading-relaxed select-text">
                            Ahorra semanas de setup y arquitectura. Laravertex combina la solidez de Laravel 13, la reactividad de Livewire 4 y la potencia de Capacitor 7 con Arquitectura Hexagonal y componentes DaisyUI 5 listos para producción.
                        </p>

                        {{-- Primary Actions / Thumb Zone --}}
                        <div class="w-full flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 pt-4 max-w-2xl mx-auto flex-wrap">
                            @auth
                                <x-button
                                    href="{{ route('dashboard') }}"
                                    variant="primary"
                                    size="lg"
                                    class="w-full sm:w-auto min-h-[48px] px-8 shadow-xl shadow-indigo-600/30 font-semibold text-base"
                                >
                                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                    </svg>
                                    <span>Ir al Dashboard</span>
                                </x-button>
                            @else
                                <x-button
                                    href="{{ route('auth.login') }}"
                                    variant="primary"
                                    size="lg"
                                    class="w-full sm:w-auto min-h-[48px] px-8 shadow-xl shadow-indigo-600/30 font-semibold text-base"
                                >
                                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    <span>Probar Demo Web</span>
                                </x-button>
                            @endauth

                            <x-button
                                href="https://github.com/devcabeza/starter-kit/releases/latest/download/app-debug.apk"
                                download="Laravertex.apk"
                                variant="neutral"
                                size="lg"
                                class="w-full sm:w-auto min-h-[48px] px-6 border border-zinc-700/80 hover:border-zinc-500 font-semibold text-base shadow-sm"
                            >
                                <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span>Descargar APK</span>
                            </x-button>

                            <x-button
                                href="https://github.com/devcabeza/starter-kit"
                                target="_blank"
                                rel="noopener noreferrer"
                                variant="neutral"
                                size="lg"
                                class="w-full sm:w-auto min-h-[48px] px-6 border border-zinc-700/80 hover:border-zinc-500 font-semibold text-base shadow-sm"
                            >
                                <svg class="w-5 h-5 fill-current text-white shrink-0" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                </svg>
                                <span>Ver en GitHub</span>
                            </x-button>
                        </div>

                        {{-- Trust Microcopy & Risk Reversal --}}
                        <div class="pt-2 text-xs sm:text-sm text-zinc-500 flex items-center justify-center gap-3 sm:gap-6 flex-wrap select-none">
                            <span class="inline-flex items-center gap-1.5 text-zinc-400">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                100% Open Source (MIT)
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-zinc-400">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Docker & Coolify Ready
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-zinc-400">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Setup en 2 minutos
                            </span>
                        </div>
                    </div>

                    {{-- Hero Showcase Mockup (Interactive Code / Stack Preview) --}}
                    <div class="mt-14 max-w-5xl mx-auto">
                        <div class="relative rounded-3xl bg-zinc-900/90 border border-zinc-800 shadow-2xl overflow-hidden backdrop-blur-xl text-left">
                            {{-- Window Top Bar --}}
                            <div class="px-4 sm:px-6 py-3.5 bg-zinc-900 border-b border-zinc-800/80 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-rose-500/80 inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                                    <span class="ml-2 text-xs font-mono text-zinc-500">laravertex ~ starter-kit</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs font-mono text-zinc-400">
                                    <x-badge variant="neutral" size="xs" class="border border-emerald-500/30 text-emerald-400 bg-emerald-500/10">
                                        Pest: 100% Passing
                                    </x-badge>
                                    <x-badge variant="neutral" size="xs" class="border border-indigo-500/30 text-indigo-400 bg-indigo-500/10 hidden sm:inline-flex">
                                        Larastan: Level 8
                                    </x-badge>
                                </div>
                            </div>

                            {{-- Code / Architecture Highlights --}}
                            <div class="p-6 sm:p-8 font-mono text-xs sm:text-sm text-zinc-300 space-y-4 overflow-x-auto select-text">
                                <div class="flex items-center gap-2 text-zinc-500">
                                    <span class="text-indigo-400 font-bold">$</span>
                                    <span class="text-zinc-400"># 1. Clona el proyecto y ejecuta el setup automatizado</span>
                                </div>
                                <div class="bg-zinc-950/70 p-4 rounded-xl border border-zinc-800/80 flex items-center justify-between gap-4">
                                    <code class="text-indigo-300 overflow-x-auto whitespace-nowrap">git clone https://github.com/devcabeza/starter-kit.git mi-app && cd mi-app && composer run setup</code>
                                    <button
                                        type="button"
                                        onclick="navigator.clipboard.writeText('git clone https://github.com/devcabeza/starter-kit.git mi-app && cd mi-app && composer run setup'); this.innerText='¡Copiado! '; setTimeout(() => this.innerText='Copiar', 2000)"
                                        class="shrink-0 px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-sans active:scale-95 transition-all select-none cursor-pointer"
                                    >
                                        Copiar
                                    </button>
                                </div>

                                <div class="flex items-center gap-2 text-zinc-500 pt-2">
                                    <span class="text-indigo-400 font-bold">$</span>
                                    <span class="text-zinc-400"># 2. Inicia el servidor de desarrollo y compila tu APK nativo</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="bg-zinc-950/70 p-4 rounded-xl border border-zinc-800/80">
                                        <div class="text-zinc-500 text-xs mb-1">Entorno Web Reactivo:</div>
                                        <code class="text-emerald-400">npm run dev</code>
                                        <div class="text-xs text-zinc-500 mt-2 font-sans">Livewire 4 + Vite Hot Module Reloading activo en http://localhost:8000</div>
                                    </div>
                                    <div class="bg-zinc-950/70 p-4 rounded-xl border border-zinc-800/80">
                                        <div class="text-zinc-500 text-xs mb-1">Compilación Android Nativa:</div>
                                        <code class="text-purple-300">npm run build:android</code>
                                        <div class="text-xs text-zinc-500 mt-2 font-sans">Capacitor 7 sincroniza vistas y Gradle genera tu app-debug.apk</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 2. TECH STACK MARQUEE / LOGOS SECTION --}}
                <section id="stack" class="w-full border-y border-zinc-800/80 bg-zinc-900/30 py-12">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <p class="text-xs font-bold text-zinc-500 uppercase tracking-widest select-none">
                            Impulsado por el ecosistema más moderno de Laravel y desarrollo móvil
                        </p>
                        <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4 items-center justify-center">
                            <div class="p-3.5 rounded-2xl bg-zinc-900/60 border border-zinc-800/80 flex flex-col items-center justify-center gap-1.5 hover:border-zinc-700 transition-colors">
                                <span class="font-bold text-white text-sm">Laravel 13</span>
                                <span class="text-[11px] text-zinc-500">PHP 8.3 / 8.4</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-zinc-900/60 border border-zinc-800/80 flex flex-col items-center justify-center gap-1.5 hover:border-zinc-700 transition-colors">
                                <span class="font-bold text-indigo-300 text-sm">Livewire 4.1</span>
                                <span class="text-[11px] text-zinc-500">Blaze 1.0</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-zinc-900/60 border border-zinc-800/80 flex flex-col items-center justify-center gap-1.5 hover:border-zinc-700 transition-colors">
                                <span class="font-bold text-emerald-400 text-sm">Capacitor 7</span>
                                <span class="text-[11px] text-zinc-500">Android & iOS</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-zinc-900/60 border border-zinc-800/80 flex flex-col items-center justify-center gap-1.5 hover:border-zinc-700 transition-colors">
                                <span class="font-bold text-purple-300 text-sm">DaisyUI 5</span>
                                <span class="text-[11px] text-zinc-500">Tailwind CSS v4</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-zinc-900/60 border border-zinc-800/80 flex flex-col items-center justify-center gap-1.5 hover:border-zinc-700 transition-colors">
                                <span class="font-bold text-white text-sm">Hexagonal</span>
                                <span class="text-[11px] text-zinc-500">Clean Code</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-zinc-900/60 border border-zinc-800/80 flex flex-col items-center justify-center gap-1.5 hover:border-zinc-700 transition-colors">
                                <span class="font-bold text-amber-400 text-sm">Pest 5</span>
                                <span class="text-[11px] text-zinc-500">100% Tests</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-zinc-900/60 border border-zinc-800/80 flex flex-col items-center justify-center gap-1.5 hover:border-zinc-700 transition-colors">
                                <span class="font-bold text-sky-400 text-sm">Docker</span>
                                <span class="text-[11px] text-zinc-500">Coolify & Cloud</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-zinc-900/60 border border-zinc-800/80 flex flex-col items-center justify-center gap-1.5 hover:border-zinc-700 transition-colors">
                                <span class="font-bold text-rose-400 text-sm">Horizon</span>
                                <span class="text-[11px] text-zinc-500">Redis Queues</span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 3. CORE BENEFITS / TRANSFORMATION SECTION --}}
                <section id="features" class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
                    <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                        <x-badge variant="neutral" size="md" class="border border-indigo-500/30 text-indigo-300">
                            Ventajas Competitivas
                        </x-badge>
                        <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight select-text">
                            Todo lo que necesitas para construir rápido y escalar en serio
                        </h2>
                        <p class="text-base sm:text-lg text-zinc-400 select-text">
                            Deja atrás los kits superficiales. Laravertex está pensado para resolver los problemas reales de distribución móvil, desacoplamiento de arquitectura y estabilidad en producción.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Benefit 1: Mobile APK --}}
                        <x-card class="bg-zinc-900/60 border-zinc-800 hover:border-indigo-500/50 p-2">
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                </div>
                                <div class="space-y-2 select-text">
                                    <h3 class="text-xl font-bold text-white">
                                        De la Web al APK Móvil Nativo sin reescribir una sola línea
                                    </h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed">
                                        No gastes miles de dólares desarrollando apps por separado en Flutter o React Native. Gracias a Capacitor 7 y utilidades preconfiguradas (<code class="text-indigo-300">pt-safe</code>, <code class="text-indigo-300">pb-safe</code>, targets táctiles de 48px y gestos nativos de Android), tu SaaS se convierte en un APK Android nativo listo para Google Play o distribución directa.
                                    </p>
                                </div>
                                <div class="pt-2">
                                    <x-badge variant="neutral" size="sm" class="border border-indigo-500/20 text-indigo-300 bg-indigo-950/30">
                                        Capacitor 7 + GitHub Actions CI/CD
                                    </x-badge>
                                </div>
                            </div>
                        </x-card>

                        {{-- Benefit 2: Livewire 4 & DaisyUI 5 --}}
                        <x-card class="bg-zinc-900/60 border-zinc-800 hover:border-indigo-500/50 p-2">
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-2xl bg-purple-600/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                </div>
                                <div class="space-y-2 select-text">
                                    <h3 class="text-xl font-bold text-white">
                                        Reactividad instantánea sin la fatiga de JavaScript
                                    </h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed">
                                        Crea experiencias de usuario fluidas y reactivas en tiempo real usando Livewire 4.1 y Blaze 1.0. Todo el estado y la validación permanecen en PHP seguro. Además, disfrutas de DaisyUI 5 encapsulado en Blade wrappers reutilizables para garantizar consistencia visual y velocidad de maquetado.
                                    </p>
                                </div>
                                <div class="pt-2">
                                    <x-badge variant="neutral" size="sm" class="border border-purple-500/20 text-purple-300 bg-purple-950/30">
                                        Livewire 4.1 + DaisyUI 5 + Tailwind v4
                                    </x-badge>
                                </div>
                            </div>
                        </x-card>

                        {{-- Benefit 3: Hexagonal Architecture --}}
                        <x-card class="bg-zinc-900/60 border-zinc-800 hover:border-indigo-500/50 p-2">
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                                    </svg>
                                </div>
                                <div class="space-y-2 select-text">
                                    <h3 class="text-xl font-bold text-white">
                                        Arquitectura Hexagonal que resiste el crecimiento
                                    </h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed">
                                        Evita el temido "código espagueti" o controladores gigantes. Laravertex separa estrictamente las entidades de Dominio, los Casos de Uso (Application Actions) y los Puertos/Adaptadores. Puedes sustituir bases de datos o pasarelas de email sin tocar tu lógica central de negocio.
                                    </p>
                                </div>
                                <div class="pt-2">
                                    <x-badge variant="neutral" size="sm" class="border border-emerald-500/20 text-emerald-300 bg-emerald-950/30">
                                        Domain-Driven Design & Ports/Adapters
                                    </x-badge>
                                </div>
                            </div>
                        </x-card>

                        {{-- Benefit 4: Batteries for Production --}}
                        <x-card class="bg-zinc-900/60 border-zinc-800 hover:border-indigo-500/50 p-2">
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-2xl bg-amber-600/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                    </svg>
                                </div>
                                <div class="space-y-2 select-text">
                                    <h3 class="text-xl font-bold text-white">
                                        Baterías completas para producción desde el día cero
                                    </h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed">
                                        Autenticación Passwordless mediante Magic Links por email (integración lista con Sendrix), colas asíncronas supervisadas con Laravel Horizon y Redis, observabilidad con Telescope y Pail, documentación OpenAPI automática con Scramble y Dockerfiles para despliegue en Coolify.
                                    </p>
                                </div>
                                <div class="pt-2">
                                    <x-badge variant="neutral" size="sm" class="border border-amber-500/20 text-amber-300 bg-amber-950/30">
                                        Horizon + Scramble + Telescope + Docker
                                    </x-badge>
                                </div>
                            </div>
                        </x-card>
                    </div>
                </section>

                {{-- 4. BENTO GRID ARCHITECTURE SHOWCASE (UI/UX 2026 TRENDS) --}}
                <section id="architecture" class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 border-t border-zinc-800/80">
                    <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                        <x-badge variant="neutral" size="md" class="border border-indigo-500/30 text-indigo-300">
                            Estructura de Alto Calibre
                        </x-badge>
                        <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight select-text">
                            Ingeniería limpia diseñada para crecer
                        </h2>
                        <p class="text-base sm:text-lg text-zinc-400 select-text">
                            Explora cómo cada capa de Laravertex colabora para entregarte una experiencia de desarrollo fluida y altamente desacoplada.
                        </p>
                    </div>

                    {{-- Bento Grid Layout --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6">
                        {{-- Bento 1: Hexagonal Flow (Spans 8 cols) --}}
                        <div class="col-span-1 md:col-span-2 lg:col-span-8">
                            <x-card class="h-full bg-zinc-900/70 border-zinc-800 hover:border-indigo-500/50 p-2 flex flex-col justify-between">
                                <div class="space-y-4 select-text">
                                    <div class="flex items-center justify-between">
                                        <x-badge variant="neutral" size="sm" class="border border-indigo-500/30 text-indigo-300 bg-indigo-950/40">
                                            Patrón Ports & Adapters
                                        </x-badge>
                                        <span class="text-xs font-mono text-zinc-500">app/Domain &bull; app/Application</span>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white">
                                        Separación Estricta de Responsabilidades
                                    </h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed">
                                        La lógica de negocio (Dominio) no conoce a Eloquent ni a HTTP. Los Casos de Uso (Acciones de Aplicación) orquestan el flujo y se comunican a través de interfaces (Puertos). Los Controladores, Livewire y comandos de consola son meros adaptadores de entrada.
                                    </p>

                                    {{-- Mini Flowchart Visual --}}
                                    <div class="pt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 font-mono text-xs">
                                        <div class="p-4 rounded-xl bg-zinc-950/80 border border-zinc-800">
                                            <div class="text-indigo-400 font-bold mb-1">Ports In (Entrada)</div>
                                            <div class="text-zinc-400 text-[11px] font-sans">Livewire 4, Controladores HTTP, Jobs, Comandos Artisan</div>
                                        </div>
                                        <div class="p-4 rounded-xl bg-zinc-950/80 border border-indigo-500/30 shadow-inner">
                                            <div class="text-emerald-400 font-bold mb-1">Core (Aplicación)</div>
                                            <div class="text-zinc-400 text-[11px] font-sans">Acciones puras de caso de uso y reglas de negocio del Dominio</div>
                                        </div>
                                        <div class="p-4 rounded-xl bg-zinc-950/80 border border-zinc-800">
                                            <div class="text-purple-400 font-bold mb-1">Ports Out (Salida)</div>
                                            <div class="text-zinc-400 text-[11px] font-sans">Repositorios Eloquent, Gateway Sendrix, Redis Queues</div>
                                        </div>
                                    </div>
                                </div>
                            </x-card>
                        </div>

                        {{-- Bento 2: Passwordless Magic Links (Spans 4 cols) --}}
                        <div class="col-span-1 md:col-span-2 lg:col-span-4">
                            <x-card class="h-full bg-zinc-900/70 border-zinc-800 hover:border-indigo-500/50 p-2 flex flex-col justify-between">
                                <div class="space-y-4 select-text">
                                    <x-badge variant="neutral" size="sm" class="border border-emerald-500/30 text-emerald-300 bg-emerald-950/40">
                                        Seguridad Cero Fricción
                                    </x-badge>
                                    <h3 class="text-xl font-bold text-white">
                                        Autenticación Passwordless
                                    </h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed">
                                        Sin contraseñas que hackear o recordar. Tus usuarios acceden con Magic Links de un solo uso por correo con expiración de 15 minutos, firma criptográfica y rate limiting por IP.
                                    </p>
                                    <div class="p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-800 text-xs font-mono text-zinc-400 space-y-1">
                                        <div class="text-emerald-400 font-bold">✓ Tokens con hash SHA-256</div>
                                        <div class="text-emerald-400 font-bold">✓ Rate limit: 5 peticiones/min</div>
                                        <div class="text-emerald-400 font-bold">✓ Honeypot anti-bots integrado</div>
                                    </div>
                                </div>
                            </x-card>
                        </div>

                        {{-- Bento 3: DaisyUI 5 Component System (Spans 4 cols) --}}
                        <div class="col-span-1 md:col-span-1 lg:col-span-4">
                            <x-card class="h-full bg-zinc-900/70 border-zinc-800 hover:border-indigo-500/50 p-2">
                                <div class="space-y-4 select-text">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-600/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-white">
                                        DaisyUI 5 + Wrappers Blade
                                    </h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed">
                                        Más de 20 componentes preconfigurados (<code class="text-indigo-300">&lt;x-button&gt;</code>, <code class="text-indigo-300">&lt;x-modal&gt;</code>, <code class="text-indigo-300">&lt;x-input&gt;</code>). Cero clases DaisyUI crudas en tus vistas; todo con fusión limpia de atributos.
                                    </p>
                                </div>
                            </x-card>
                        </div>

                        {{-- Bento 4: Pest 5 & Larastan QA (Spans 4 cols) --}}
                        <div class="col-span-1 md:col-span-1 lg:col-span-4">
                            <x-card class="h-full bg-zinc-900/70 border-zinc-800 hover:border-indigo-500/50 p-2">
                                <div class="space-y-4 select-text">
                                    <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693l-1.57-.393m15.6 0l1.4 3.5A2.25 2.25 0 0119 21.75H5a2.25 2.25 0 01-2.2-2.95l1.4-3.5" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-white">
                                        Calidad y QA Automatizado
                                    </h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed">
                                        Suite completa de tests con Pest 5, tests de arquitectura estrictos, análisis estático con Larastan Nivel 8 y formateo de estilo con Laravel Pint en hooks de pre-commit.
                                    </p>
                                </div>
                            </x-card>
                        </div>

                        {{-- Bento 5: Coolify & Docker Multistage (Spans 4 cols) --}}
                        <div class="col-span-1 md:col-span-2 lg:col-span-4">
                            <x-card class="h-full bg-zinc-900/70 border-zinc-800 hover:border-indigo-500/50 p-2">
                                <div class="space-y-4 select-text">
                                    <div class="w-10 h-10 rounded-xl bg-sky-600/10 border border-sky-500/30 flex items-center justify-center text-sky-400">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-white">
                                        Despliegue Instantáneo
                                    </h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed">
                                        Docker Compose para desarrollo local y Dockerfiles optimizados para Staging y Producción en Coolify o servidores VPS propios con Nginx y PHP-FPM 8.4 tuneados.
                                    </p>
                                </div>
                            </x-card>
                        </div>
                    </div>
                </section>

                {{-- 5. QUICKSTART / HOW IT WORKS IN 3 STEPS --}}
                <section id="quickstart" class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                    <div class="text-center mb-16 space-y-4">
                        <x-badge variant="neutral" size="md" class="border border-indigo-500/30 text-indigo-300">
                            Puesta en Marcha
                        </x-badge>
                        <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight select-text">
                            Listo para crear en 3 sencillos pasos
                        </h2>
                        <p class="text-base sm:text-lg text-zinc-400 select-text">
                            De cero a un entorno full-stack funcionando en menos de 3 minutos.
                        </p>
                    </div>

                    <div class="space-y-6">
                        {{-- Step 1 --}}
                        <div class="p-6 sm:p-8 rounded-3xl bg-zinc-900/60 border border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white font-bold flex items-center justify-center shrink-0">
                                    1
                                </div>
                                <div class="space-y-1 select-text">
                                    <h3 class="text-lg font-bold text-white">Clona el repositorio</h3>
                                    <p class="text-sm text-zinc-400">Descarga el código en tu máquina local o servidor.</p>
                                </div>
                            </div>
                            <div class="bg-zinc-950 px-4 py-3 rounded-xl border border-zinc-800/80 font-mono text-xs text-indigo-300 select-all overflow-x-auto">
                                git clone https://github.com/devcabeza/starter-kit.git mi-saas
                            </div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="p-6 sm:p-8 rounded-3xl bg-zinc-900/60 border border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white font-bold flex items-center justify-center shrink-0">
                                    2
                                </div>
                                <div class="space-y-1 select-text">
                                    <h3 class="text-lg font-bold text-white">Ejecuta el asistente de setup</h3>
                                    <p class="text-sm text-zinc-400">Instala dependencias, genera claves, migra BD SQLite y compila assets.</p>
                                </div>
                            </div>
                            <div class="bg-zinc-950 px-4 py-3 rounded-xl border border-zinc-800/80 font-mono text-xs text-emerald-400 select-all overflow-x-auto">
                                cd mi-saas && composer run setup
                            </div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="p-6 sm:p-8 rounded-3xl bg-zinc-900/60 border border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white font-bold flex items-center justify-center shrink-0">
                                    3
                                </div>
                                <div class="space-y-1 select-text">
                                    <h3 class="text-lg font-bold text-white">Inicia el servidor y compila</h3>
                                    <p class="text-sm text-zinc-400">Arranca el servidor local o genera tu APK móvil nativo con Capacitor.</p>
                                </div>
                            </div>
                            <div class="bg-zinc-950 px-4 py-3 rounded-xl border border-zinc-800/80 font-mono text-xs text-purple-300 select-all overflow-x-auto">
                                npm run dev &bull; npm run build:android
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 6. OBJECTION HANDLING / FAQ SECTION --}}
                <section id="faq" class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-zinc-800/80">
                    <div class="text-center mb-16 space-y-4">
                        <x-badge variant="neutral" size="md" class="border border-indigo-500/30 text-indigo-300">
                            Preguntas Frecuentes
                        </x-badge>
                        <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight select-text">
                            Resolvemos todas tus dudas
                        </h2>
                        <p class="text-base sm:text-lg text-zinc-400 select-text">
                            Todo lo que necesitas saber antes de empezar a programar con Laravertex.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <x-collapse title="¿Por qué elegir Laravertex en lugar de Laravel Breeze o Jetstream?" :open="true">
                            Breeze y Jetstream son excelentes para autenticación inicial simple, pero están limitados a la web tradicional y carecen de arquitectura desacoplada. Laravertex es un ecosistema completo: incluye soporte móvil nativo con Capacitor 7 (generando APKs Android reales), Arquitectura Hexagonal que previene deuda técnica, colas asíncronas con Laravel Horizon ya configuradas, componentes DaisyUI 5 encapsulados en Blade y recetas de despliegue para Docker y Coolify.
                        </x-collapse>

                        <x-collapse title="¿La aplicación móvil con Capacitor es realmente nativa o un WebView lento?">
                            Capacitor 7 renderiza sobre el WebView del sistema acelerado por GPU con acceso a APIs nativas (cámara, notificaciones push, almacenamiento, splash screen y botón de retroceso de Android). Combinado con las utilidades de área segura (<code class="text-indigo-300">pt-safe</code>, <code class="text-indigo-300">pb-safe</code>), fuentes de 16px para prevenir auto-zoom molesto y targets táctiles ergonómicos de 48px, la experiencia táctil se siente idéntica a una aplicación nativa tradicional.
                        </x-collapse>

                        <x-collapse title="¿Necesito instalar Android Studio para compilar el APK?">
                            Para desarrollo local puedes usar Android Studio si deseas probar en emuladores. Sin embargo, el repositorio incluye un flujo de trabajo para GitHub Actions que compila automáticamente tu APK en la nube y genera releases descargables sin requerir que configures el SDK de Android en tu máquina.
                        </x-collapse>

                        <x-collapse title="¿Es difícil adaptar mis ideas si no conozco Arquitectura Hexagonal?">
                            No. Laravertex está diseñado para ser pragmático: si quieres crear una ruta rápida o un componente Livewire directo, puedes hacerlo. Pero cuando tu SaaS crezca, contarás con una estructura limpia organizada en Dominio, Casos de Uso (Application) y Puertos, lo que te permitirá añadir funcionalidades complejas sin romper el código existente.
                        </x-collapse>

                        <x-collapse title="¿Puedo usar Laravertex para proyectos comerciales o de clientes?">
                            Totalmente. Laravertex se distribuye bajo la licencia MIT, lo que significa que tienes libertad completa para construir proyectos personales, comerciales, productos SaaS de pago o sitios para clientes sin pagar ninguna regalía ni tener restricciones de atribución obligatoria.
                        </x-collapse>
                    </div>
                </section>

                {{-- 7. FINAL HIGH-URGENCY CTA SECTION --}}
                <section class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
                    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-b from-zinc-900 via-zinc-900 to-zinc-950 border border-indigo-500/30 p-8 sm:p-16 text-center shadow-2xl">
                        {{-- Subtle background glow inside banner --}}
                        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-indigo-500/20 blur-[100px] rounded-full pointer-events-none"></div>

                        <div class="relative z-10 max-w-3xl mx-auto space-y-6">
                            <x-badge variant="neutral" size="md" class="border border-indigo-400/30 text-indigo-300 bg-indigo-950/60">
                                Despliega hoy mismo
                            </x-badge>

                            <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight select-text">
                                Deja de perder días en configuración. Enfócate en tu producto.
                            </h2>

                            <p class="text-base sm:text-lg text-zinc-300 max-w-xl mx-auto leading-relaxed select-text">
                                Miles de líneas de configuración, pruebas y arquitectura listas para ser clonadas. Lanza tu SaaS web y móvil hoy.
                            </p>

                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4 max-w-xl mx-auto w-full flex-wrap">
                                @auth
                                    <x-button
                                        href="{{ route('dashboard') }}"
                                        variant="primary"
                                        size="lg"
                                        class="w-full sm:w-auto min-h-[48px] px-8 shadow-xl shadow-indigo-600/30 font-semibold text-base"
                                    >
                                        Ir al Dashboard
                                    </x-button>
                                @else
                                    <x-button
                                        href="{{ route('auth.login') }}"
                                        variant="primary"
                                        size="lg"
                                        class="w-full sm:w-auto min-h-[48px] px-8 shadow-xl shadow-indigo-600/30 font-semibold text-base"
                                    >
                                        Probar Demo Web
                                    </x-button>
                                @endauth

                                <x-button
                                    href="https://github.com/devcabeza/starter-kit/releases/latest/download/app-debug.apk"
                                    download="Laravertex.apk"
                                    variant="neutral"
                                    size="lg"
                                    class="w-full sm:w-auto min-h-[48px] px-6 border border-zinc-700/80 font-semibold text-base"
                                >
                                    Descargar APK Móvil
                                </x-button>

                                <x-button
                                    href="https://github.com/devcabeza/starter-kit"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    variant="neutral"
                                    size="lg"
                                    class="w-full sm:w-auto min-h-[48px] px-6 border border-zinc-700/80 hover:border-zinc-500 font-semibold text-base shadow-sm"
                                >
                                    <svg class="w-5 h-5 fill-current text-white shrink-0" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                    </svg>
                                    <span>Ver Repositorio</span>
                                </x-button>
                            </div>

                            <p class="pt-2 text-xs text-zinc-500 select-none">
                                Licencia MIT &bull; 100% Gratuito y Libre de Ataduras &bull; Soporte para Coolify y Docker
                            </p>
                        </div>
                    </div>
                </section>
            </main>

            {{-- Safe-Area Compliant Footer --}}
            <footer class="w-full border-t border-zinc-800/80 bg-zinc-950 py-10 px-4 sm:px-6 lg:px-8 select-none">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('favicon.svg') }}" alt="Laravertex" class="w-6 h-6">
                        <span class="font-bold text-sm text-white">Laravertex Starter Kit</span>
                        <span class="text-zinc-600 text-sm">&bull;</span>
                        <span class="text-xs text-zinc-500">&copy; {{ date('Y') }} MIT License</span>
                    </div>

                    <div class="flex items-center gap-6 text-xs text-zinc-400">
                        <a href="#features" class="hover:text-zinc-200 transition-colors">Ventajas</a>
                        <a href="#architecture" class="hover:text-zinc-200 transition-colors">Arquitectura</a>
                        <a href="#stack" class="hover:text-zinc-200 transition-colors">Stack</a>
                        <a href="#faq" class="hover:text-zinc-200 transition-colors">FAQ</a>
                        <a href="https://github.com/devcabeza/starter-kit" target="_blank" rel="noopener noreferrer" class="hover:text-indigo-400 transition-colors font-medium">GitHub</a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
