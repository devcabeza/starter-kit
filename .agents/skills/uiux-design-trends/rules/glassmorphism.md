# Glassmorphism & Soft UI

## Core Concept

Glassmorphism creates depth through translucent layers with backdrop blur, creating a frosted glass effect.

## CSS Classes (Tailwind)

### Basic Glass Card
```blade
<div class="
    relative overflow-hidden
    bg-white/10 dark:bg-black/20
    backdrop-blur-xl backdrop-saturate-150
    border border-white/20 dark:border-white/10
    rounded-2xl shadow-xl
">
    {{ $slot }}
</div>
```

### Glass Variants
```blade
{{-- Light glass --}}
<div class="bg-white/5 backdrop-blur-sm border border-white/10">

{{-- Medium glass (default) --}}
<div class="bg-white/10 backdrop-blur-xl border border-white/20">

{{-- Strong glass --}}
<div class="bg-white/20 backdrop-blur-2xl border border-white/30">

{{-- Dark glass --}}
<div class="bg-black/10 backdrop-blur-xl border border-white/10">
```

### Gradient Overlay Effect
```blade
<div class="
    relative overflow-hidden
    bg-white/10 backdrop-blur-xl
    rounded-2xl
">
    {{-- Gradient overlay --}}
    <div class="
        absolute inset-0
        bg-gradient-to-br 
        from-white/20 via-transparent to-transparent
        pointer-events-none
    "/>
    
    {{-- Content --}}
    <div class="relative z-10 p-6">
        {{ $slot }}
    </div>
</div>
```

## Soft UI (Neumorphism) Alternative

```blade
{{-- Soft UI with shadows --}}
<div class="
    bg-gray-100 dark:bg-dark-900
    rounded-2xl
    shadow-[8px_8px_16px_rgba(0,0,0,0.1),-8px_-8px_16px_rgba(255,255,255,0.9)]
    dark:shadow-[8px_8px_16px_rgba(0,0,0,0.3),-8px_-8px_16px_rgba(255,255,255,0.05)]
">
    {{ $slot }}
</div>
```

## Best Practices

1. **Use sparingly** - Glass works best for cards, modals, overlays
2. **Ensure contrast** - Text must be readable over glass
3. **Layer depth** - Use z-index to stack glass elements
4. **Test dark mode** - Glass looks different in dark theme
5. **Performance** - backdrop-blur can be expensive on mobile
