@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'type' => 'text',
    'error' => null,
    'hint' => null,
    'size' => 'md',
    'bordered' => true,
])

@php
$inputClasses = 'input w-full min-h-[44px] transition-colors focus:outline-none';

$sizeClasses = match ($size) {
    'xs' => 'input-xs min-h-[36px]',
    'sm' => 'input-sm min-h-[40px]',
    'lg' => 'input-lg min-h-[48px]',
    default => 'input-md min-h-[44px]',
};

$borderClass = $bordered ? 'input-bordered' : '';

// Resolve validation error from props or Laravel's shared $errors bag
$viewErrors = $errors ?? view()->shared('errors') ?? new \Illuminate\Support\ViewErrorBag();
$hasError = !empty($error) || ($name && $viewErrors->has($name));
$errorMessage = $error ?? ($name && $viewErrors->has($name) ? $viewErrors->first($name) : null);
$errorClass = $hasError ? 'input-error border-error text-error' : '';

$inputId = $id ?? $name ?? 'input-' . uniqid();
@endphp

<div class="form-control w-full space-y-1.5">
    @if ($label)
        <label for="{{ $inputId }}" class="label py-0 px-0.5">
            <span class="label-text font-medium text-sm text-zinc-300">{{ $label }}</span>
        </label>
    @endif

    <input
        type="{{ $type }}"
        @if ($name) name="{{ $name }}" @endif
        id="{{ $inputId }}"
        {{ $attributes->class([$inputClasses, $sizeClasses, $borderClass, $errorClass]) }}
    />

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
