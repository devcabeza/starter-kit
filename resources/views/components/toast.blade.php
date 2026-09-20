@props([
    'position' => 'bottom-end', // 'top-start', 'top-center', 'top-end', 'bottom-start', 'bottom-center', 'bottom-end'
])

@php
$positionClasses = match ($position) {
    'top-start' => 'toast-top toast-start',
    'top-center' => 'toast-top toast-center',
    'top-end' => 'toast-top toast-end',
    'bottom-start' => 'toast-bottom toast-start',
    'bottom-center' => 'toast-bottom toast-center',
    default => 'toast-bottom toast-end',
};
@endphp

<div {{ $attributes->class(['toast z-50 p-4', $positionClasses]) }}>
    {{ $slot }}
</div>
