@props([
    'shape' => 'rectangle', // 'rectangle', 'circle', 'text'
])

@php
$shapeClasses = match ($shape) {
    'circle' => 'rounded-full',
    'text' => 'h-4 w-full rounded',
    default => 'rounded-lg',
};
@endphp

<div {{ $attributes->class(['skeleton bg-zinc-800/80 animate-pulse', $shapeClasses]) }}></div>
