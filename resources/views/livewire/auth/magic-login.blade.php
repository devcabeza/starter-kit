<div class="flex-1 flex flex-col justify-center items-center px-4 py-8 sm:px-6 lg:px-8 pt-safe pb-safe">
    <div class="w-full max-w-md mx-auto space-y-8">
        {{-- Header & Branding --}}
        <div class="text-center space-y-3">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 shadow-inner">
                <img src="{{ asset('favicon.svg') }}" alt="Laravertex" class="w-10 h-10 select-none">
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                Acceso sin contraseña
            </h1>
            <p class="text-sm text-zinc-400 max-w-sm mx-auto">
                Inicia sesión o regístrate con tu correo. Te enviaremos un código seguro de acceso directo.
            </p>
        </div>

        {{-- Form Card --}}
        <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
            <form wire:submit="submit" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-300 mb-2">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <input
                            id="email"
                            type="email"
                            inputmode="email"
                            autocomplete="email"
                            autocapitalize="none"
                            spellcheck="false"
                            wire:model="email"
                            placeholder="tu@ejemplo.com"
                            class="w-full min-h-[48px] px-4 py-3 text-base rounded-xl bg-zinc-950 border border-zinc-700/80 text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            required
                        >
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-rose-400 font-medium flex items-center gap-1.5">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <div>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full min-h-[48px] px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-base shadow-lg shadow-indigo-600/25 transition-all duration-150 active:scale-95 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="submit">Continuar con Email</span>
                        <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Enviando código...
                        </span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-zinc-800 text-center">
                <p class="text-xs text-zinc-400 leading-relaxed">
                    Al continuar, si tu cuenta no existe será creada automáticamente. No necesitas recordar ninguna contraseña.
                </p>
            </div>
        </div>

        {{-- Back home link --}}
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-sm font-medium text-zinc-400 hover:text-zinc-200 transition-colors inline-flex items-center gap-1.5 active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Volver al inicio
            </a>
        </div>
    </div>
</div>
