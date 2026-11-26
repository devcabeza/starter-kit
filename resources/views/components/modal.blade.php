@props([
    'id',
    'title' => null,
    'actions' => null,
])

<dialog id="{{ $id }}" {{ $attributes->class(['modal modal-bottom sm:modal-middle']) }}>
    <div class="modal-box bg-zinc-900 border border-zinc-800 text-zinc-100 rounded-t-3xl rounded-b-none sm:rounded-3xl max-h-[90vh] overflow-y-auto overscroll-contain p-6 pb-[calc(1.5rem+env(safe-area-inset-bottom,0px))] sm:pb-6 shadow-2xl space-y-4">
        {{-- Drag Handle (mobile bottom sheet) --}}
        <div class="sm:hidden w-12 h-1.5 mx-auto -mt-1 mb-1 rounded-full bg-zinc-700" aria-hidden="true"></div>

        {{-- Modal Header --}}
        <div class="flex items-center justify-between pb-2 border-b border-zinc-800/80">
            @if ($title)
                <h3 class="text-lg font-bold text-white tracking-tight">
                    {{ $title }}
                </h3>
            @endif

            <form method="dialog">
                <button class="btn btn-md btn-circle btn-ghost min-h-[44px] min-w-[44px] text-zinc-400 hover:text-white" aria-label="Cerrar modal">
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
            <div class="modal-action pt-4 border-t border-zinc-800/80 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 [&>*]:w-full sm:[&>*]:w-auto [&_button]:w-full sm:[&_button]:w-auto">
                {{ $actions }}
            </div>
        @endif
    </div>

    {{-- Backdrop to close when clicking outside --}}
    <form method="dialog" class="modal-backdrop bg-black/60 backdrop-blur-xs">
        <button>cerrar</button>
    </form>
</dialog>
