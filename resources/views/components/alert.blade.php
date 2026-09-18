@props([
    'type' => 'info',
    'icon' => null,
    'dismissible' => false,
])

@php
$baseClasses = 'alert shadow-sm flex items-start sm:items-center gap-3 p-4 rounded-2xl text-sm border';

$typeClasses = match ($type) {
    'success' => 'alert-success text-emerald-100 border-emerald-500/30 bg-emerald-950/40',
    'warning' => 'alert-warning text-amber-100 border-amber-500/30 bg-amber-950/40',
    'danger', 'error' => 'alert-error text-rose-100 border-rose-500/30 bg-rose-950/40',
    default => 'alert-info text-sky-100 border-sky-500/30 bg-sky-950/40',
};
@endphp

<div
    role="alert"
    @if ($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif
    {{ $attributes->class([$baseClasses, $typeClasses]) }}
>
    @if ($icon)
        <div class="shrink-0">
            {{ $icon }}
        </div>
    @else
        <div class="shrink-0 mt-0.5 sm:mt-0">
            @if ($type === 'success')
                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @elseif ($type === 'warning')
                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            @elseif ($type === 'danger' || $type === 'error')
                <svg class="w-5 h-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 7.5h.008v.008H12v-.008z" />
                </svg>
            @else
                <svg class="w-5 h-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
            @endif
        </div>
    @endif

    <div class="flex-1 leading-relaxed">
        {{ $slot }}
    </div>

    @if ($dismissible)
        <button
            type="button"
            @click="show = false"
            class="btn btn-ghost btn-xs btn-circle text-zinc-400 hover:text-white"
            aria-label="Cerrar alerta"
        >
            ✕
        </button>
    @endif
</div>
