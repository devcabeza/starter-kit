@props([
    'align' => 'end',
    'position' => 'bottom',
    'width' => 'w-60',
    'menuClasses' => '',
])

@php
$baseClasses = 'dropdown select-none';

$alignClass = match ($align) {
    'start', 'left' => 'dropdown-start',
    'center' => 'dropdown-center',
    'end', 'right' => 'dropdown-end',
    default => 'dropdown-end',
};

$positionClass = match ($position) {
    'top' => 'dropdown-top',
    'left' => 'dropdown-left',
    'right' => 'dropdown-right',
    default => 'dropdown-bottom',
};
@endphp

<div {{ $attributes->class([$baseClasses, $alignClass, $positionClass]) }}>
    <div tabindex="0" role="button" class="cursor-pointer focus:outline-none min-h-[44px] inline-flex items-center active:scale-95 transition-transform duration-150">
        {{ $trigger }}
    </div>

    <ul tabindex="0" class="dropdown-content menu bg-zinc-900/95 backdrop-blur-xl border border-zinc-800/90 text-zinc-200 rounded-2xl z-50 p-2 shadow-2xl space-y-1 {{ $width }} {{ $menuClasses }}">
        {{ $slot }}
    </ul>
</div>
