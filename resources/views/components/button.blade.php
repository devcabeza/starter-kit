@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'loading' => false,
    'icon' => null,
    'outline' => false,
])

@php
$baseClasses = 'btn active:scale-95 transition-all select-none duration-150 inline-flex items-center justify-center gap-2 cursor-pointer font-medium';

$variantClasses = match ($variant) {
    'primary' => 'btn-primary',
    'secondary' => 'btn-secondary',
    'accent' => 'btn-accent',
    'neutral' => 'btn-neutral',
    'ghost' => 'btn-ghost',
    'link' => 'btn-link',
    'danger', 'error' => 'btn-error text-white',
    'success' => 'btn-success text-white',
    'warning' => 'btn-warning text-zinc-950',
    'info' => 'btn-info text-white',
    default => 'btn-primary',
};

$sizeClasses = match ($size) {
    'xs' => 'btn-xs min-h-[36px]',
    'sm' => 'btn-sm min-h-[40px]',
    'lg' => 'btn-lg min-h-[48px]',
    'xl' => 'btn-xl min-h-[56px]',
    default => 'btn-md min-h-[44px]',
};

$outlineClass = $outline ? 'btn-outline' : '';
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->class([$baseClasses, $variantClasses, $sizeClasses, $outlineClass]) }}
    >
        @if ($loading)
            <span class="loading loading-spinner loading-xs"></span>
        @elseif ($icon)
            {{ $icon }}
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->class([$baseClasses, $variantClasses, $sizeClasses, $outlineClass]) }}
        @if ($loading) disabled @endif
    >
        @if ($loading)
            <span class="loading loading-spinner loading-xs"></span>
        @elseif ($icon)
            {{ $icon }}
        @endif
        {{ $slot }}
    </button>
@endif
