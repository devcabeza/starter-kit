# Reusable Blade Components

## Bento Card Component

```blade
{{-- resources/views/components/bento-card.blade.php --}}
@props([
    'variant' => 'default',
    'size' => 'medium',
    'href' => null,
    'class' => '',
])

@php
    $tag = $href ? 'a' : 'div';
    $sizes = [
        'small' => 'col-span-12 sm:col-span-6 lg:col-span-4',
        'medium' => 'col-span-12 sm:col-span-6 lg:col-span-4 lg:row-span-2',
        'large' => 'col-span-12 lg:col-span-8 lg:row-span-2',
        'full' => 'col-span-12',
    ];
    $variants = [
        'default' => 'bg-white dark:bg-dark-900 border border-gray-200 dark:border-gray-800',
        'primary' => 'bg-gradient-to-br from-blue-500 to-purple-600 text-white',
        'glass' => 'bg-white/10 dark:bg-black/20 backdrop-blur-xl border border-white/20',
        'gradient' => 'bg-gradient-to-br from-cyan-500 to-blue-500 text-white',
    ];
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge([
        'class' => "relative overflow-hidden rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 {$sizes[$size]} {$variants[$variant]} {$class}"
    ]) }}
    role="{{ $href ? 'link' : 'region' }}"
    tabindex="0"
>
    <div class="relative z-10 p-6 h-full">
        {{ $slot }}
    </div>
</{{ $tag }}>
```

## Glass Card Component

```blade
{{-- resources/views/components/glass-card.blade.php --}}
@props([
    'blur' => 'xl',
    'opacity' => '10',
    'class' => '',
])

<div {{
    $attributes->merge([
        'class' => "relative overflow-hidden rounded-2xl bg-white/{$opacity} dark:bg-black/{$opacity} backdrop-blur-{$blur} border border-white/20 dark:border-white/10 shadow-xl {$class}"
    ])
}}>
    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none" />
    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>
```

## Metric Display Component

```blade
{{-- resources/views/components/metric-display.blade.php --}}
@props([
    'value',
    'label',
    'trend' => null,
    'icon' => null,
])

<div class="flex items-center justify-between">
    <div>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
        <p class="text-3xl font-bold mt-1">{{ $value }}</p>
        @if($trend)
            <p class="text-sm mt-1 {{ $trend > 0 ? 'text-green-500' : 'text-red-500' }}">
                {{ $trend > 0 ? '↑' : '↓' }} {{ abs($trend) }}%
            </p>
        @endif
    </div>
    @if($icon)
        <div class="text-4xl opacity-50">
            {{ $icon }}
        </div>
    @endif
</div>
```

## Button Component with Microinteractions

```blade
{{-- resources/views/components/button.blade.php --}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
])

@php
    $variants = [
        'primary' => 'bg-blue-500 hover:bg-blue-600 text-white',
        'secondary' => 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white',
        'ghost' => 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-900 dark:text-white',
    ];
    $sizes = [
        'sm' => 'min-h-[36px] px-3 py-1.5 text-sm',
        'md' => 'min-h-[44px] px-4 py-2.5 text-base',
        'lg' => 'min-h-[52px] px-6 py-3 text-lg',
    ];
@endphp

<button
    {{ $attributes->merge([
        'class' => "inline-flex items-center justify-center rounded-xl font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed {$variants[$variant]} {$sizes[$size]}"
    ]) }}
    @if($loading) disabled @endif
>
    @if($loading)
        <x-spinner class="mr-2" />
    @endif
    {{ $slot }}
</button>
```

## Usage Examples

```blade
{{-- Dashboard layout --}}
<x-bento-card size="large" variant="primary">
    <x-metric-display 
        :value="'$' . number_format($revenue, 2)" 
        label="Total Revenue"
        :trend="12.5"
    />
</x-bento-card>

<x-bento-card size="small" variant="glass">
    <x-metric-display 
        :value="$users" 
        label="Active Users"
    />
</x-bento-card>

{{-- Glass modal --}}
<x-glass-card blur="2xl" opacity="20">
    <h2 class="text-xl font-bold">Modal Title</h2>
    <p class="mt-2">Modal content here</p>
</x-glass-card>
```

## Best Practices

1. **Composition over inheritance** - Compose components, don't extend
2. **Slot-based** - Use slots for content, attributes for styling
3. **Accessible by default** - Include ARIA attributes
4. **Responsive** - Mobile-first design
5. **Theme-aware** - Support light/dark modes
