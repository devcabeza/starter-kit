# Microinteractions & Motion Design

## Core Concept

Animations with purpose that communicate state, not decoration.

## Livewire + Alpine Patterns

### Hover Effects
```blade
<div 
    x-data="{ isHovered: false }"
    x-on:mouseenter="isHovered = true"
    x-on:mouseleave="isHovered = false"
    class="
        transition-all duration-300 ease-out
        {{ $isHovered ? 'scale-[1.02] shadow-2xl' : 'scale-100 shadow-lg' }}
    "
>
    {{ $slot }}
</div>
```

### Click Feedback
```blade
<button 
    x-data="{ isPressed: false }"
    x-on:mousedown="isPressed = true"
    x-on:mouseup="isPressed = false"
    x-on:mouseleave="isPressed = false"
    class="
        transition-transform duration-100
        {{ $isPressed ? 'scale-95' : 'scale-100' }}
    "
>
    {{ $label }}
</button>
```

### Loading States
```blade
<div 
    x-data="{ isLoading: false }"
    wire:loading.target="submit"
    wire:loading.class="opacity-50 pointer-events-none"
>
    {{-- Content --}}
</div>

{{-- Loading spinner --}}
<div wire:loading>
    <x-spinner />
</div>
```

### Success/Error Feedback
```blade
<div 
    x-data="{ 
        showSuccess: false,
        showError: false 
    }"
    wire:show-success="showSuccess = true; setTimeout(() => showSuccess = false, 2000)"
    wire:show-error="showError = true; setTimeout(() => showError = false, 2000)"
>
    {{-- Success message --}}
    <div 
        x-show="showSuccess"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg"
    >
        Saved successfully!
    </div>
</div>
```

## CSS Animation Classes

```blade
{{-- Fade in --}}
<div class="animate-fade-in">

{{-- Slide up --}}
<div class="animate-slide-up">

{{-- Scale in --}}
<div class="animate-scale-in">

{{-- Glow effect --}}
<div class="animate-glow">
```

## Scroll-Based Animations

```blade
<div 
    x-data="{ 
        isVisible: false 
    }"
    x-intersect:enter="isVisible = true"
    x-intersect:leave="isVisible = false"
    class="
        transition-all duration-700 ease-out
        {{ $isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8' }}
    "
>
    {{ $slot }}
</div>
```

## Best Practices

1. **Purpose over decoration** - Every animation must communicate something
2. **Keep it subtle** - 200-300ms duration for most interactions
3. **Use easing** - ease-out for entrances, ease-in for exits
4. **Test performance** - Use transform and opacity only when possible
5. **Respect preferences** - Check `prefers-reduced-motion`
