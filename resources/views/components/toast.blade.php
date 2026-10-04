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

// Protect bottom-anchored toasts from Android/iOS gesture bars
$safeClass = str_starts_with($position, 'bottom')
    ? 'pb-[calc(1rem+env(safe-area-inset-bottom,0px))]'
    : '';

$responsiveClass = 'w-full sm:w-auto max-w-[calc(100vw-1rem)]';
@endphp

<div {{ $attributes->class(['toast z-50 p-4', $responsiveClass, $safeClass, $positionClasses]) }}>
    {{ $slot }}
</div>
