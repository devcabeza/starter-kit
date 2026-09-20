@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'error' => null,
    'hint' => null,
    'size' => 'md',
    'bordered' => true,
])

@php
$fileClasses = 'file-input w-full min-h-[44px] transition-colors focus:outline-none';

$sizeClasses = match ($size) {
    'xs' => 'file-input-xs min-h-[36px]',
    'sm' => 'file-input-sm min-h-[40px]',
    'lg' => 'file-input-lg min-h-[48px]',
    default => 'file-input-md min-h-[44px]',
};

$borderClass = $bordered ? 'file-input-bordered' : '';

$viewErrors = $errors ?? view()->shared('errors') ?? new \Illuminate\Support\ViewErrorBag();
$hasError = !empty($error) || ($name && $viewErrors->has($name));
$errorMessage = $error ?? ($name && $viewErrors->has($name) ? $viewErrors->first($name) : null);
$errorClass = $hasError ? 'file-input-error border-error' : '';

$fileId = $id ?? $name ?? 'file-' . uniqid();
@endphp

<div class="form-control w-full space-y-1.5">
    @if ($label)
        <label for="{{ $fileId }}" class="label py-0 px-0.5">
            <span class="label-text font-medium text-sm text-zinc-300">{{ $label }}</span>
        </label>
    @endif

    <input
        type="file"
        @if ($name) name="{{ $name }}" @endif
        id="{{ $fileId }}"
        {{ $attributes->class([$fileClasses, $sizeClasses, $borderClass, $errorClass]) }}
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
