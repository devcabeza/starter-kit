# Accessibility Standards (WCAG)

## Core Requirements

### Touch Targets
```blade
{{-- Minimum 44x44px touch targets --}}
<button class="
    min-h-[44px] min-w-[44px]
    px-4 py-3
    flex items-center justify-center
">
    {{ $label }}
</button>

{{-- 48x48px for mobile (recommended) --}}
<button class="
    min-h-[48px] min-w-[48px]
    px-5 py-3
">
    {{ $label }}
</button>
```

### Contrast Ratios
```blade
{{-- Minimum 4.5:1 for normal text --}}
<span class="text-gray-900 dark:text-gray-100">
    High contrast text
</span>

{{-- 3:1 for large text (18px+ or 14px+ bold) --}}
<h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
    Large heading
</h1>
```

### Keyboard Navigation
```blade
{{-- Focusable elements --}}
<button 
    class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    tabindex="0"
>
    {{ $label }}
</button>

{{-- Skip navigation --}}
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4">
    Skip to main content
</a>
```

### ARIA Labels
```blade
{{-- Icon buttons --}}
<button 
    aria-label="Close dialog"
    class="min-h-[44px] min-w-[44px]"
>
    <x-icon-close />
</button>

{{-- Navigation --}}
<nav aria-label="Main navigation">
    <ul>
        <li><a href="/" aria-current="page">Home</a></li>
        <li><a href="/about">About</a></li>
    </ul>
</nav>

{{-- Live regions --}}
<div 
    wire:live 
    aria-live="polite" 
    aria-atomic="true"
>
    {{-- Dynamic content updates --}}
</div>
```

## Screen Reader Support

```blade
{{-- Visually hidden but accessible --}}
<span class="sr-only">
    Current page: {{ $pageTitle }}
</span>

{{-- Descriptive links --}}
<a href="/users/{{ $user->id }}">
    View profile for {{ $user->name }}
    <span class="sr-only">(opens in new tab)</span>
</a>
```

## Reduced Motion

```blade
{{-- Respect user preferences --}}
<div 
    x-data="{ 
        prefersReducedMotion: false 
    }"
    x-init="prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches"
    class="{{ $prefersReducedMotion ? '' : 'transition-all duration-300' }}"
>
    {{ $slot }}
</div>
```

```css
/* CSS fallback */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
```

## Testing Checklist

- [ ] All interactive elements are keyboard accessible
- [ ] Touch targets are at least 44x44px
- [ ] Color contrast meets WCAG AA (4.5:1)
- [ ] Images have alt text
- [ ] Forms have labels
- [ ] Error messages are announced to screen readers
- [ ] Focus order is logical
- [ ] Reduced motion is respected
