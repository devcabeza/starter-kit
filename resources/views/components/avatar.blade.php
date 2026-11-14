@props([
    'src' => null,
    'alt' => null,
    'initials' => null,
    'size' => 'md',
    'shape' => 'circle',
    'status' => null,
])

@php
$baseClasses = 'avatar select-none';

$statusClass = match ($status) {
    'online' => 'avatar-online',
    'offline' => 'avatar-offline',
    default => '',
};

$placeholderClass = ($initials || ! $src) ? 'avatar-placeholder' : '';

$sizeClasses = match ($size) {
    'xs' => 'w-7 h-7 text-xs',
    'sm' => 'w-9 h-9 text-sm',
    'lg' => 'w-12 h-12 text-base',
    'xl' => 'w-16 h-16 text-xl',
    default => 'w-10 h-10 text-sm',
};

$shapeClass = match ($shape) {
    'rounded' => 'rounded-xl',
    'square' => 'rounded-none',
    default => 'rounded-full',
};
@endphp

<div {{ $attributes->class([$baseClasses, $statusClass, $placeholderClass]) }}>
    <div class="{{ $shapeClass }} {{ $sizeClasses }} bg-indigo-600/20 border border-indigo-500/30 text-indigo-300 font-semibold flex items-center justify-center">
        @if ($src)
            <img src="{{ $src }}" alt="{{ $alt ?? 'Avatar' }}" class="object-cover w-full h-full" />
        @elseif ($initials)
            <span>{{ $initials }}</span>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
