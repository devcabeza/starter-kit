@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'error' => null,
    'hint' => null,
    'rows' => 3,
    'bordered' => true,
])

@php
$textareaClasses = 'textarea w-full transition-colors focus:outline-none';
$borderClass = $bordered ? 'textarea-bordered' : '';

$viewErrors = $errors ?? view()->shared('errors') ?? new \Illuminate\Support\ViewErrorBag();
$hasError = !empty($error) || ($name && $viewErrors->has($name));
$errorMessage = $error ?? ($name && $viewErrors->has($name) ? $viewErrors->first($name) : null);
$errorClass = $hasError ? 'textarea-error border-error text-error' : '';

$textareaId = $id ?? $name ?? 'textarea-' . uniqid();
@endphp

<div class="form-control w-full space-y-1.5">
    @if ($label)
        <label for="{{ $textareaId }}" class="label py-0 px-0.5">
            <span class="label-text font-medium text-sm text-zinc-300">{{ $label }}</span>
        </label>
    @endif

    <textarea
        @if ($name) name="{{ $name }}" @endif
        id="{{ $textareaId }}"
        rows="{{ $rows }}"
        {{ $attributes->class([$textareaClasses, $borderClass, $errorClass]) }}
    >{{ $slot }}</textarea>

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
