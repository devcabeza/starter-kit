{{--
    Mobile Bottom Navigation Component
    Optimized for Capacitor APK WebView & Mobile Touch
    Features:
    - Safe-area bottom padding (pb-safe) for Android gesture bars & iOS home indicators
    - Touch-optimized minimum tap targets (>= 48px height)
    - Active state visual feedback (active:scale-95)
    - Hidden on desktop/tablet (md:hidden)
--}}

@props([
    'active' => 'home',
])

<nav class="fixed bottom-0 inset-x-0 z-40 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-md border-t border-zinc-200 dark:border-zinc-800 pb-safe md:hidden select-none touch-callout-none transition-all duration-200">
    <div class="grid grid-cols-4 items-center h-16 px-2">
        {{-- Home Tab --}}
        <a href="{{ route('dashboard') }}"
           class="flex flex-col items-center justify-center min-h-[48px] min-w-[48px] py-1 rounded-xl transition-transform duration-100 active:scale-95 {{ $active === 'home' ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200' }}">
            <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $active === 'home' ? '2.2' : '1.8' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-xs tracking-tight">Inicio</span>
        </a>

        {{-- Activity / Explore Tab --}}
        <a href="#"
           class="flex flex-col items-center justify-center min-h-[48px] min-w-[48px] py-1 rounded-xl transition-transform duration-100 active:scale-95 {{ $active === 'activity' ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200' }}">
            <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $active === 'activity' ? '2.2' : '1.8' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-xs tracking-tight">Actividad</span>
        </a>

        {{-- Notifications Tab --}}
        <a href="#"
           class="relative flex flex-col items-center justify-center min-h-[48px] min-w-[48px] py-1 rounded-xl transition-transform duration-100 active:scale-95 {{ $active === 'notifications' ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200' }}">
            <div class="relative">
                <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $active === 'notifications' ? '2.2' : '1.8' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute -top-0.5 -right-1 flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </span>
            </div>
            <span class="text-xs tracking-tight">Alertas</span>
        </a>

        {{-- Profile / Settings Tab --}}
        <a href="#"
           class="flex flex-col items-center justify-center min-h-[48px] min-w-[48px] py-1 rounded-xl transition-transform duration-100 active:scale-95 {{ $active === 'profile' ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200' }}">
            <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $active === 'profile' ? '2.2' : '1.8' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-xs tracking-tight">Perfil</span>
        </a>
    </div>
</nav>
