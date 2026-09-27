---
name: uiux-design-trends
description: "Apply this skill when creating or modifying Livewire components, Blade views, CSS/Tailwind styles, or any frontend UI. Incorporates 2026 design trends: Glassmorphism, Bento Grids, Microinteractions, Kinetic Typography, Dark Mode, and Accessibility standards. Use for: new page layouts, component design, responsive layouts, animations, dark mode implementation, and any visual design decisions."
license: MIT
metadata:
  author: starter-kit
---

# UI/UX Design Trends 2026

**Livewire + Blade + Tailwind Implementation Guide**

This skill provides practical implementation patterns for modern UI/UX trends in Laravel Livewire applications.

## Core Design Principles

### 1. Generative & Hyper-Personalized Interfaces
```blade
{{-- Dynamic layout based on user context --}}
<div class="{{ $user->preferences->layout_style ?? 'default' }}">
    {{-- Content adapts to user behavior --}}
    @foreach($personalizedItems as $item)
        <x-dynamic-component 
            :component="$item->component" 
            :data="$item->data" 
        />
    @endforeach
</div>
```

### 2. Bento Grid Layouts
```blade
{{-- Apple-inspired bento grid --}}
<div class="grid grid-cols-12 gap-4 auto-rows-[minmax(180px,auto)]">
    {{-- Main metric - spans 8 columns --}}
    <div class="col-span-8 row-span-2">
        <x-bento-card variant="primary">
            <x-metric-display :value="$revenue" label="Revenue" />
        </x-bento-card>
    </div>
    
    {{-- Side metrics - 4 columns each --}}
    <div class="col-span-4">
        <x-bento-card variant="glass">
            <x-metric-display :value="$users" label="Users" />
        </x-bento-card>
    </div>
    
    <div class="col-span-4">
        <x-bento-card variant="glass">
            <x-metric-display :value="$orders" label="Orders" />
        </x-bento-card>
    </div>
    
    {{-- Full width section --}}
    <div class="col-span-12">
        <x-bento-card variant="gradient">
            <x-chart :data="$chartData" />
        </x-bento-card>
    </div>
</div>
```

### 3. Glassmorphism & Soft UI
```blade
{{-- Glass card with backdrop blur --}}
<div class="
    relative overflow-hidden rounded-2xl
    bg-white/10 dark:bg-black/20
    backdrop-blur-xl backdrop-saturate-150
    border border-white/20 dark:border-white/10
    shadow-xl shadow-black/5
    before:absolute before:inset-0 
    before:bg-gradient-to-br before:from-white/10 before:to-transparent
    before:pointer-events-none
">
    <div class="relative z-10 p-6">
        {{ $slot }}
    </div>
</div>
```

### 4. Microinteractions & Motion Design
```blade
{{-- Livewire with Alpine.js for microinteractions --}}
<div 
    x-data="{ 
        isHovered: false, 
        isClicked: false 
    }"
    x-on:mouseenter="isHovered = true"
    x-on:mouseleave="isHovered = false"
    x-on:click="isClicked = true; setTimeout(() => isClicked = false, 200)"
    class="
        transition-all duration-300 ease-out
        transform-gpu
        {{ $isHovered ? 'scale-[1.02] shadow-2xl' : 'scale-100 shadow-lg' }}
        {{ $isClicked ? 'scale-[0.98]' : '' }}
    "
>
    {{ $slot }}
</div>
```

### 5. Kinetic Typography
```blade
{{-- Variable font with scroll-based animation --}}
<div 
    x-data="{ 
        scrollY: 0,
        get fontWeight() {
            return Math.min(900, 300 + (this.scrollY / 5));
        }
    }"
    x-on:scroll.window="scrollY = window.scrollY"
    class="font-variable"
    :style="`font-variation-settings: 'wght' ${fontWeight}`"
>
    <h1 class="text-6xl font-bold tracking-tight">
        Dynamic Heading
    </h1>
</div>
```

### 6. Dark Mode Native
```blade
{{-- Dark mode with OLED-optimized colors --}}
<div class="
    bg-gray-50 dark:bg-[#0a0a0a]
    text-gray-900 dark:text-gray-100
    transition-colors duration-300
">
    {{-- OLED-optimized dark palette --}}
    <div class="
        bg-white dark:bg-[#111111]
        border-gray-200 dark:border-gray-800
    ">
        {{-- Content --}}
    </div>
</div>
```

## Design Tokens (Tailwind Config)

```javascript
// tailwind.config.js
module.exports = {
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // OLED-optimized dark palette
                dark: {
                    50: '#f8f9fa',
                    100: '#f1f3f5',
                    200: '#e9ecef',
                    300: '#dee2e6',
                    400: '#ced4da',
                    500: '#adb5bd',
                    600: '#868e96',
                    700: '#495057',
                    800: '#343a40',
                    900: '#212529',
                    950: '#0a0a0a', // OLED black
                },
                // Glassmorphism palette
                glass: {
                    light: 'rgba(255, 255, 255, 0.1)',
                    medium: 'rgba(255, 255, 255, 0.15)',
                    strong: 'rgba(255, 255, 255, 0.2)',
                },
            },
            backdropBlur: {
                xs: '2px',
                '2xl': '40px',
                '3xl': '64px',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'scale-in': 'scaleIn 0.3s ease-out',
                'glow': 'glow 2s ease-in-out infinite alternate',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                scaleIn: {
                    '0%': { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                glow: {
                    '0%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.5)' },
                    '100%': { boxShadow: '0 0 40px rgba(59, 130, 246, 0.8)' },
                },
            },
        },
    },
}
```

## Livewire Component Patterns

### Interactive Card with Microinteractions
```php
<?php
// app/Livewire/Components/BentoCard.php

declare(strict_types=1);

namespace App\Livewire\Components;

use Livewire\Component;

class BentoCard extends Component
{
    public string $variant = 'default';
    public bool $isHovered = false;
    public bool $isExpanded = false;
    
    public function hydrate(): void
    {
        // Animation state initialization
    }
    
    public function toggleExpand(): void
    {
        $this->isExpanded = !$this->isExpanded;
        $this->dispatch('card-toggled', ['expanded' => $this->isExpanded]);
    }
    
    public function render()
    {
        return view('livewire.components.bento-card');
    }
}
```

### Blade Component
```blade
{{-- resources/views/livewire/components/bento-card.blade.php --}}

<div 
    wire:Hover
    x-data="{ 
        isHovered: false,
        isClicked: false 
    }"
    x-on:mouseenter="isHovered = true"
    x-on:mouseleave="isHovered = false"
    x-on:mousedown="isClicked = true"
    x-on:mouseup="isClicked = false"
    x-on:click="wire.toggleExpand()"
    class="
        relative overflow-hidden rounded-2xl
        transition-all duration-300 ease-out
        transform-gpu will-change-transform
        {{ match($variant) {
            'primary' => 'bg-gradient-to-br from-blue-500 to-purple-600 text-white',
            'glass' => 'bg-white/10 dark:bg-black/20 backdrop-blur-xl border border-white/20',
            'gradient' => 'bg-gradient-to-br from-cyan-500 to-blue-500',
            default => 'bg-white dark:bg-dark-900 border border-gray-200 dark:border-gray-800',
       } }}
        {{ $isHovered ? 'shadow-2xl scale-[1.02]' : 'shadow-lg scale-100' }}
        {{ $isClicked ? 'scale-[0.98]' : '' }}
        {{ $isExpanded ? 'col-span-full row-span-2' : '' }}
    "
    role="button"
    tabindex="0"
    x-on:keydown.enter="wire.toggleExpand()"
    x-on:keydown.space.prevent="wire.toggleExpand()"
>
    {{-- Glow effect on hover --}}
    <div 
        x-show="isHovered"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"
    />
    
    {{-- Content --}}
    <div class="relative z-10 p-6">
        {{ $slot }}
    </div>
</div>
```

## Responsive Breakpoints (Mobile First)

```blade
{{-- Mobile-first responsive bento grid --}}
<div class="
    grid grid-cols-1 
    sm:grid-cols-2 
    md:grid-cols-4 
    lg:grid-cols-12 
    gap-4
    auto-rows-[minmax(150px,auto)]
">
    {{-- Mobile: full width --}}
    <div class="col-span-1 sm:col-span-2 lg:col-span-8">
        {{ $slot }}
    </div>
</div>
```

## Touch Target Sizing

```blade
{{-- Ensure minimum 44x44px touch targets --}}
<button class="
    min-h-[44px] min-w-[44px]
    px-4 py-3
    flex items-center justify-center
    {{-- Visual styling --}}
    rounded-xl bg-blue-500 text-white
    hover:bg-blue-600 active:bg-blue-700
    transition-colors duration-200
">
    {{ $label }}
</button>
```

## How to Apply

1. **New Pages:** Start with bento grid layout, add glassmorphism cards
2. **Components:** Use Livewire + Alpine for microinteractions
3. **Styling:** Apply design tokens, use backdrop-blur for glass effects
4. **Animations:** Add purposeful motion (hover, click, scroll)
5. **Dark Mode:** Test with OLED-optimized colors
6. **Accessibility:** Verify touch targets, contrast, keyboard nav

## Related Skills

- **laravel-best-practices:** Backend patterns
- **livewire-development:** Livewire-specific patterns
- **tailwindcss-development:** Tailwind utilities
- **testing-best-practices:** Test UI components

---

**REMEMBER:** Every animation must have a purpose. Every color must have contrast. Every target must be touchable.
