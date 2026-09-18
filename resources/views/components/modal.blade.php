@props([
    'id',
    'title' => null,
    'actions' => null,
])

<dialog id="{{ $id }}" {{ $attributes->class(['modal modal-bottom sm:modal-middle']) }}>
    <div class="modal-box bg-zinc-900 border border-zinc-800 text-zinc-100 rounded-3xl p-6 shadow-2xl space-y-4">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between pb-2 border-b border-zinc-800/80">
            @if ($title)
                <h3 class="text-lg font-bold text-white tracking-tight">
                    {{ $title }}
                </h3>
            @endif

            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost text-zinc-400 hover:text-white" aria-label="Cerrar modal">
                    ✕
                </button>
            </form>
        </div>

        {{-- Modal Body --}}
        <div class="text-sm text-zinc-300 space-y-3">
            {{ $slot }}
        </div>

        {{-- Modal Actions --}}
        @if ($actions)
            <div class="modal-action pt-4 border-t border-zinc-800/80 flex items-center justify-end gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>

    {{-- Backdrop to close when clicking outside --}}
    <form method="dialog" class="modal-backdrop bg-black/60 backdrop-blur-xs">
        <button>cerrar</button>
    </form>
</dialog>
