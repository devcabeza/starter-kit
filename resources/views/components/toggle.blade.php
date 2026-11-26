@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'color' => 'primary',
    'size' => 'md',
])

@php
$colorClasses = match ($color) {
    'primary' => 'toggle-primary',
    'secondary' => 'toggle-secondary',
    'accent' => 'toggle-accent',
    'success' => 'toggle-success',
    'warning' => 'toggle-warning',
    'error' => 'toggle-error',
    default => 'toggle-primary',
};

$sizeClasses = match ($size) {
    'xs' => 'toggle-xs',
    'sm' => 'toggle-sm',
    'lg' => 'toggle-lg',
    default => 'toggle-md',
};

$toggleId = $id ?? $name ?? 'toggle-' . uniqid();
@endphp

<div class="form-control">
    <label for="{{ $toggleId }}" class="label cursor-pointer justify-between gap-3 py-1.5 min-h-[44px]">
        @if ($label || !$slot->isEmpty())
            <span class="label-text text-sm text-zinc-300 font-medium select-none">{{ $label ?? $slot }}</span>
        @endif
        <input
            type="checkbox"
            @if ($name) name="{{ $name }}" @endif
            id="{{ $toggleId }}"
            {{ $attributes->class(['toggle transition-all', $colorClasses, $sizeClasses]) }}
        />
    </label>
</div>
