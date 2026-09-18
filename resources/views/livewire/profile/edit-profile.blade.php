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
                <h1 class="font-bold text-lg tracking-tight text-white">Mi Perfil</h1>
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ route('settings') }}"
                    class="px-3 py-2 text-xs font-semibold rounded-xl bg-zinc-800/80 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-all min-h-[44px] flex items-center gap-2 active:scale-95"
                >
                    <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Configuraciones</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 py-8 space-y-6">
        @if ($saved)
            <x-alert type="success" :dismissible="true">
                Tu perfil se ha actualizado correctamente.
            </x-alert>
        @endif

        {{-- Overview Card with Avatar --}}
        <x-card>
            <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                <x-avatar :initials="$user->initials()" size="xl" class="ring-2 ring-indigo-500/30" />

                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-white tracking-tight">{{ $user->name }}</h2>
                    <p class="text-sm text-zinc-400">{{ $user->email }}</p>

                    <div class="pt-1 flex flex-wrap gap-2">
                        @if ($user->email_verified_at)
                            <x-badge variant="success" size="sm">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Correo verificado
                            </x-badge>
                        @else
                            <x-badge variant="warning" size="sm" outline>
                                Pendiente de verificación
                            </x-badge>
                        @endif

                        <x-badge variant="neutral" size="sm">
                            Miembro desde {{ $user->created_at?->format('M Y') ?? 'reciente' }}
                        </x-badge>
                    </div>
                </div>
            </div>
        </x-card>

        {{-- Form Card --}}
        <x-card title="Información Personal">
            <form wire:submit="save" class="space-y-6">
                <div class="space-y-4">
                    <x-input
                        name="name"
                        label="Nombre Completo"
                        type="text"
                        placeholder="Tu nombre completo"
                        wire:model="name"
                    />

                    <x-input
                        name="email"
                        label="Correo Electrónico"
                        type="email"
                        placeholder="tu@correo.com"
                        wire:model="email"
                        hint="Si cambias tu correo, este será el nuevo destino para tus Magic Links."
                    />
                </div>

                <div class="pt-2 flex justify-end">
                    <x-button
                        type="submit"
                        variant="primary"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="w-full sm:w-auto"
                    >
                        <span wire:loading.remove wire:target="save">Guardar Cambios</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                            <span class="loading loading-spinner loading-xs"></span>
                            Guardando...
                        </span>
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
