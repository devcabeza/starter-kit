<div class="min-h-screen-safe flex flex-col pt-safe pb-safe">
    {{-- Top Navbar --}}
    <header class="border-b border-zinc-800/80 bg-zinc-900/50 backdrop-blur-xl sticky top-0 z-30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('dashboard') }}"
                    class="w-10 h-10 rounded-xl bg-zinc-800 hover:bg-zinc-700 flex items-center justify-center text-zinc-300 hover:text-white transition-all active:scale-95 select-none"
                    aria-label="Volver al dashboard"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <h1 class="font-bold text-lg tracking-tight text-white">Configuraciones</h1>
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ route('profile') }}"
                    class="px-3 py-2 text-xs font-semibold rounded-xl bg-zinc-800/80 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-all min-h-[44px] flex items-center gap-2 active:scale-95"
                >
                    <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span>Mi Perfil</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 py-8 space-y-6">
        {{-- Security & Authentication Card --}}
        <x-card title="Seguridad y Autenticación">
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-zinc-800/40 border border-zinc-800">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h4 class="text-base font-semibold text-white">Autenticación Sin Contraseña</h4>
                            <x-badge variant="success" size="sm">Activo</x-badge>
                        </div>
                        <p class="text-sm text-zinc-400">
                            Tu cuenta utiliza Magic Links de un solo uso enviados a tu correo. No requieres recordar ni cambiar contraseñas.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-zinc-800/40 border border-zinc-800">
                    <div class="space-y-1">
                        <h4 class="text-base font-semibold text-white">Sesión Activa</h4>
                        <p class="text-sm text-zinc-400">
                            Conectado actualmente desde tu navegador con el correo <strong class="text-zinc-200">{{ $user->email }}</strong>.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <x-button type="submit" variant="ghost" size="sm" class="border border-zinc-700 text-zinc-300 hover:text-white">
                            Cerrar esta sesión
                        </x-button>
                    </form>
                </div>
            </div>
        </x-card>

        {{-- Danger Zone Card --}}
        <x-card title="Zona de Peligro">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-rose-500/5 border border-rose-500/20">
                <div class="space-y-1">
                    <h4 class="text-base font-semibold text-rose-300">Eliminar Cuenta</h4>
                    <p class="text-sm text-zinc-400">
                        Una vez eliminada tu cuenta, todos tus datos y tokens de acceso se suprimirán de forma permanente. Esta acción no se puede deshacer.
                    </p>
                </div>

                <x-button
                    type="button"
                    variant="danger"
                    size="sm"
                    onclick="document.getElementById('delete-account-modal').showModal()"
                    class="shrink-0"
                >
                    Eliminar Cuenta
                </x-button>
            </div>
        </x-card>
    </div>

    {{-- Confirmation Modal --}}
    <x-modal id="delete-account-modal" title="Confirmar eliminación de cuenta">
        <p class="text-zinc-300">
            ¿Estás absolutamente seguro de que deseas eliminar tu cuenta? Todos tus datos se borrarán irreversiblemente y se cerrará tu sesión de inmediato.
        </p>

        <x-slot:actions>
            <form method="dialog">
                <x-button variant="ghost" size="sm">
                    Cancelar
                </x-button>
            </form>

            <x-button
                type="button"
                variant="danger"
                size="sm"
                wire:click="deleteAccount"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="deleteAccount">Sí, eliminar mi cuenta</span>
                <span wire:loading wire:target="deleteAccount" class="inline-flex items-center gap-2">
                    <span class="loading loading-spinner loading-xs"></span>
                    Eliminando...
                </span>
            </x-button>
        </x-slot:actions>
    </x-modal>
</div>
