@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'error' => null,
    'hint' => null,
    'size' => 'md',
    'bordered' => true,
    'placeholder' => null,
    'options' => [],
])

@php
$selectClasses = 'select w-full min-h-[44px] transition-colors focus:outline-none';

$sizeClasses = match ($size) {
    'xs' => 'select-xs min-h-[36px]',
    'sm' => 'select-sm min-h-[40px]',
    'lg' => 'select-lg min-h-[48px]',
    default => 'select-md min-h-[44px]',
};

$borderClass = $bordered ? 'select-bordered' : '';

$viewErrors = $errors ?? view()->shared('errors') ?? new \Illuminate\Support\ViewErrorBag();
$hasError = !empty($error) || ($name && $viewErrors->has($name));
$errorMessage = $error ?? ($name && $viewErrors->has($name) ? $viewErrors->first($name) : null);
$errorClass = $hasError ? 'select-error border-error text-error' : '';

$selectId = $id ?? $name ?? 'select-' . uniqid();
@endphp

<div class="form-control w-full space-y-1.5">
    @if ($label)
        <label for="{{ $selectId }}" class="label py-0 px-0.5">
            <span class="label-text font-medium text-sm text-zinc-300">{{ $label }}</span>
        </label>
    @endif

    <select
        @if ($name) name="{{ $name }}" @endif
        id="{{ $selectId }}"
        {{ $attributes->class([$selectClasses, $sizeClasses, $borderClass, $errorClass]) }}
    >
        @if ($placeholder)
            <option value="" disabled selected>{{ $placeholder }}</option>
        @endif

        @if (!empty($options))
            @foreach ($options as $val => $optLabel)
                <option value="{{ $val }}">{{ $optLabel }}</option>
            @endforeach
        @endif

        {{ $slot }}
    </select>

    @if ($hasError && $errorMessage)
        <label class="label py-0 px-0.5">
            <span class="label-text-alt text-error text-xs font-medium">{{ $errorMessage }}</span>
        </label>
    @elseif ($hint)
        <label class="label py-0 px-0.5">
            <span class="label-text-alt text-zinc-400 text-xs">{{ $hint }}</span>
        </label>
    @endif
</div>
