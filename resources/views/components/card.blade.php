@props([
    'title' => null,
    'bordered' => true,
    'compact' => false,
    'actions' => null,
    'figure' => null,
])

@php
$baseClasses = 'card bg-zinc-900/70 text-zinc-100 transition-colors shadow-sm';
$borderClass = $bordered ? 'border border-zinc-800 hover:border-zinc-700' : '';
$compactClass = $compact ? 'card-compact' : '';
@endphp

<div {{ $attributes->class([$baseClasses, $borderClass, $compactClass]) }}>
    @if ($figure)
        <figure>
            {{ $figure }}
        </figure>
    @endif

    <div class="card-body p-6 space-y-4">
        @if ($title || isset($header))
            @if (isset($header))
                {{ $header }}
            @else
                <h3 class="card-title text-lg font-bold text-white tracking-tight">
                    {{ $title }}
                </h3>
            @endif
        @endif

        <div class="text-sm text-zinc-400 space-y-3">
            {{ $slot }}
        </div>

        @if ($actions || isset($footer))
            <div class="card-actions justify-end pt-2">
                {{ $actions ?? $footer }}
            </div>
        @endif
    </div>
</div>
