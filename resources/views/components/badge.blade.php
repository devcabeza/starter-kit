@props([
    'variant' => 'neutral',
    'size' => 'md',
    'outline' => false,
])

@php
$baseClasses = 'badge font-medium gap-1.5 transition-colors select-none';

$variantClasses = match ($variant) {
    'primary' => 'badge-primary',
    'secondary' => 'badge-secondary',
    'accent' => 'badge-accent',
    'neutral' => 'badge-neutral',
    'ghost' => 'badge-ghost',
    'success' => 'badge-success text-white',
    'warning' => 'badge-warning text-zinc-950',
    'danger', 'error' => 'badge-error text-white',
    'info' => 'badge-info text-white',
    default => 'badge-neutral',
};

$sizeClasses = match ($size) {
    'xs' => 'badge-xs text-[10px]',
    'sm' => 'badge-sm text-xs',
    'lg' => 'badge-lg text-sm px-3 py-2',
    'xl' => 'badge-xl text-base px-4 py-3',
    default => 'badge-md text-xs',
};

$outlineClass = $outline ? 'badge-outline' : '';
@endphp

<span {{ $attributes->class([$baseClasses, $variantClasses, $sizeClasses, $outlineClass]) }}>
    {{ $slot }}
</span>
