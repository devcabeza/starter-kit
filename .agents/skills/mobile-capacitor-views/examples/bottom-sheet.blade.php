{{--
    Mobile Bottom Sheet (Drawer) Component
    Replaces desktop centered modal on mobile viewports for Capacitor APK
    Features:
    - Slide-up bottom sheet animation using Alpine.js
    - Drag/Pill handle at top for native mobile look and feel
    - Safe-area bottom padding (pb-safe)
    - Full thumb-zone reachable action buttons
    - Accessible backdrop click and ESC key dismiss
--}}

@props([
    'title' => 'Detalles',
    'id' => 'mobile-bottom-sheet',
])

<div x-data="{ open: false }"
     x-on:open-sheet.window="if ($event.detail.id === '{{ $id }}') open = true"
     x-on:close-sheet.window="if ($event.detail.id === '{{ $id }}') open = false"
     x-on:keydown.escape.window="open = false"
     class="relative z-50">

    {{-- Backdrop overlay --}}
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-on:click="open = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

    {{-- Sliding Sheet Panel --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed inset-x-0 bottom-0 max-h-[90vh] flex flex-col bg-white dark:bg-zinc-900 rounded-t-3xl shadow-2xl border-t border-zinc-200 dark:border-zinc-800 pb-safe">

        {{-- Drag Handle / Pill Header --}}
        <div class="pt-3 pb-2 flex flex-col items-center justify-center cursor-grab active:cursor-grabbing select-none"
             x-on:click="open = false">
            <div class="w-12 h-1.5 bg-zinc-300 dark:bg-zinc-700 rounded-full"></div>
            <div class="w-full px-6 pt-3 flex items-center justify-between">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                    {{ $title }}
                </h3>
                <button type="button"
                        x-on:click="open = false"
                        class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-full active:scale-95 transition-transform">
                    <span class="sr-only">Cerrar</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Scrollable Content Body --}}
        <div class="px-6 py-4 overflow-y-auto flex-1 overscroll-contain text-zinc-700 dark:text-zinc-300 text-base">
            {{ $slot ?? 'Contenido principal del bottom sheet.' }}
        </div>

        {{-- Bottom Actions (Reachable in thumb zone) --}}
        <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-900/50 border-t border-zinc-100 dark:border-zinc-800 flex gap-3">
            <button type="button"
                    x-on:click="open = false"
                    class="flex-1 min-h-[48px] inline-flex items-center justify-center px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-xl text-base font-semibold text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 active:scale-95 transition-transform">
                Cancelar
            </button>
            <button type="button"
                    class="flex-1 min-h-[48px] inline-flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-semibold rounded-xl shadow-md active:scale-95 transition-transform">
                Confirmar
            </button>
        </div>
    </div>
</div>
