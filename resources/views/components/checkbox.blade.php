@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'color' => 'primary',
    'size' => 'md',
    'error' => null,
])

@php
$colorClasses = match ($color) {
    'primary' => 'checkbox-primary',
    'secondary' => 'checkbox-secondary',
    'accent' => 'checkbox-accent',
    'success' => 'checkbox-success',
    'warning' => 'checkbox-warning',
    'error' => 'checkbox-error',
    default => 'checkbox-primary',
};

$sizeClasses = match ($size) {
    'xs' => 'checkbox-xs',
    'sm' => 'checkbox-sm',
    'lg' => 'checkbox-lg',
    default => 'checkbox-md',
};

$checkboxId = $id ?? $name ?? 'checkbox-' . uniqid();
@endphp

<div class="form-control">
    <label for="{{ $checkboxId }}" class="label cursor-pointer justify-start gap-3 py-1.5 min-h-[44px]">
        <input
            type="checkbox"
            @if ($name) name="{{ $name }}" @endif
            id="{{ $checkboxId }}"
            {{ $attributes->class(['checkbox transition-all', $colorClasses, $sizeClasses]) }}
        />
        @if ($label || !$slot->isEmpty())
            <span class="label-text text-sm text-zinc-300 font-medium select-none">{{ $label ?? $slot }}</span>
        @endif
    </label>
</div>
