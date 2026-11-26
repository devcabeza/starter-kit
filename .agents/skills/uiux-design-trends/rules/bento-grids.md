# Bento Grid Layouts

## Core Concept

Bento grids (inspired by Apple) organize content in asymmetric, modular cards that are scannable and clean.

## Basic Bento Grid

```blade
<div class="
    grid grid-cols-12 
    gap-4 
    auto-rows-[minmax(180px,auto)]
">
    {{-- Main content - 8 columns, 2 rows --}}
    <div class="col-span-8 row-span-2">
        <x-bento-card variant="primary">
            {{ $mainContent }}
        </x-bento-card>
    </div>
    
    {{-- Side content - 4 columns, 1 row each --}}
    <div class="col-span-4">
        <x-bento-card variant="glass">
            {{ $sideContent1 }}
        </x-bento-card>
    </div>
    
    <div class="col-span-4">
        <x-bento-card variant="glass">
            {{ $sideContent2 }}
        </x-bento-card>
    </div>
    
    {{-- Full width - 12 columns --}}
    <div class="col-span-12">
        <x-bento-card variant="gradient">
            {{ $fullWidthContent }}
        </x-bento-card>
    </div>
</div>
```

## Responsive Bento

```blade
{{-- Mobile: 1 column --}}
{{-- Tablet: 2 columns --}}
{{-- Desktop: 12 columns --}}
<div class="
    grid 
    grid-cols-1 
    sm:grid-cols-2 
    lg:grid-cols-12 
    gap-4
    auto-rows-[minmax(150px,auto)]
">
    <div class="
        col-span-1 
        sm:col-span-2 
        lg:col-span-8
        lg:row-span-2
    ">
        {{ $slot }}
    </div>
</div>
```

## Bento Card Sizes

| Size | Columns | Rows | Use Case |
|------|---------|------|----------|
| Small | 4 | 1 | Metrics, quick actions |
| Medium | 6 | 1 | Charts, lists |
| Large | 8 | 2 | Main content, dashboards |
| Full | 12 | 1 | Full-width sections |

## Animation Patterns

```blade
{{-- Staggered entrance animation --}}
<div 
    x-data="{ delay: 0 }"
    x-init="delay = $el.dataset.index * 100"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    :style="`transition-delay: ${delay}ms`"
    class="col-span-{{ $size }}"
>
    {{ $slot }}
</div>
```

## Best Practices

1. **Maintain hierarchy** - Main content gets more space
2. **Use consistent spacing** - Gap-4 or Gap-6 throughout
3. **Align to grid** - Don't break the grid structure
4. **Responsive first** - Design mobile, then expand
5. **Visual balance** - Distribute visual weight evenly
