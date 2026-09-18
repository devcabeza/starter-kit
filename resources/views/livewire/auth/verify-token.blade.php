<div class="flex-1 flex flex-col justify-center items-center px-4 py-8 sm:px-6 lg:px-8 pt-safe pb-safe">
    <div class="w-full max-w-md mx-auto space-y-8">
        {{-- Header & Instructions --}}
        <div class="text-center space-y-3">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 shadow-inner">
                <svg class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                Revisa tu correo
            </h1>
            <p class="text-sm text-zinc-400 max-w-sm mx-auto">
                Hemos enviado un código de 6 dígitos a:
                <br>
                <span class="font-semibold text-white break-all">{{ $email }}</span>
            </p>
        </div>

        {{-- Verification Card --}}
        <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
            @if ($errorMessage)
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 text-rose-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <span>{{ $errorMessage }}</span>
                </div>
            @endif

            @if ($successMessage)
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $successMessage }}</span>
                </div>
            @endif

            <form wire:submit="verify" class="space-y-6">
                <div>
                    <label for="token" class="block text-sm font-medium text-zinc-300 text-center mb-3">
                        Introduce el código de acceso
                    </label>
                    <input
                        id="token"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        autocomplete="one-time-code"
                        placeholder="••••••"
                        wire:model="token"
                        autofocus
                        class="w-full min-h-[58px] px-4 py-3 text-center text-3xl font-mono tracking-[0.35em] rounded-xl bg-zinc-950 border border-zinc-700/80 text-white placeholder-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    >
                </div>

                <div>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full min-h-[48px] px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-base shadow-lg shadow-indigo-600/25 transition-all duration-150 active:scale-95 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="verify">Verificar y Entrar</span>
                        <span wire:loading wire:target="verify" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Verificando código...
                        </span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-zinc-800 flex items-center justify-between text-xs">
                <button
                    type="button"
                    wire:click="resend"
                    wire:loading.attr="disabled"
                    class="font-medium text-indigo-400 hover:text-indigo-300 transition-colors cursor-pointer active:scale-95 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="resend">¿No recibiste el código? Reenviar</span>
                    <span wire:loading wire:target="resend">Reenviando...</span>
                </button>

                <a href="{{ route('auth.login') }}" wire:navigate class="text-zinc-500 hover:text-zinc-300 transition-colors">
                    Cambiar correo
                </a>
            </div>
        </div>

        {{-- Help notice --}}
        <p class="text-center text-xs text-zinc-500">
            Revisa también tu bandeja de spam si no ves el correo en unos segundos.
        </p>
    </div>
</div>
