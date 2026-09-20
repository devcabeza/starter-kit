@props([
    'size' => 'md',
    'color' => 'primary',
])

@php
$sizeClasses = match ($size) {
    'xs' => 'loading-xs',
    'sm' => 'loading-sm',
    'lg' => 'loading-lg',
    default => 'loading-md',
};

$colorClasses = match ($color) {
    'primary' => 'text-indigo-500',
    'secondary' => 'text-secondary',
    'accent' => 'text-accent',
    'white' => 'text-white',
    'current' => 'text-current',
    default => 'text-indigo-500',
};
@endphp

<span {{ $attributes->class(['loading loading-spinner', $sizeClasses, $colorClasses]) }} role="status" aria-label="Cargando"></span>
