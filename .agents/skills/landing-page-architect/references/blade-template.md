# Blade Template Quick Reference

## Component Patterns

### CTA Button (Reusable)
```blade
@props([
    'href' => '#',
    'variant' => 'primary',
    'size' => 'lg',
])

@php
    $base = 'inline-flex items-center justify-center font-semibold rounded-lg transition-colors shadow-lg';
    $variants = [
        'primary' => 'bg-emerald-500 text-white hover:bg-emerald-600',
        'secondary' => 'bg-white text-indigo-600 hover:bg-gray-100',
        'outline' => 'border-2 border-white text-white hover:bg-white/10',
    ];
    $sizes = [
        'md' => 'px-6 py-3 text-base',
        'lg' => 'px-8 py-4 text-lg',
    ];
@endphp

<a href="{{ $href }}" class="{{ $base }} {{ $variants[$variant] }} {{ $sizes[$size] }}">
    {{ $slot }}
</a>
```

### Trust Badge Row
```blade
<div class="flex flex-wrap justify-center items-center gap-6 text-sm text-gray-500">
    <span class="flex items-center gap-1">
        <x-heroicon-o-shield-check class="w-4 h-4" />
        SSL Seguro
    </span>
    <span class="flex items-center gap-1">
        <x-heroicon-o-clock class="w-4 h-4" />
        Setup en 2 minutos
    </span>
    <span class="flex items-center gap-1">
        <x-heroicon-o-x-mark class="w-4 h-4" />
        Sin tarjeta de crédito
    </span>
</div>
```

### FAQ Accordion (with Alpine.js)
```blade
<div x-data="{ open: null }" class="space-y-4">
    @foreach($faqs as $i => $faq)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <button
                @click="open = open === {{ $i }} ? null : {{ $i }}"
                class="w-full px-6 py-4 text-left flex justify-between items-center"
            >
                <span class="font-semibold text-gray-900">{{ $faq['question'] }}</span>
                <x-heroicon-o-chevron-down class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open === {{ $i }} }" />
            </button>
            <div x-show="open === {{ $i }}" x-collapse>
                <p class="px-6 pb-4 text-gray-600">{{ $faq['answer'] }}</p>
            </div>
        </div>
    @endforeach
</div>
```

## Color Palette Defaults

| Purpose | Tailwind Classes | Hex |
|---|---|---|
| Primary (trust) | `indigo-600`, `indigo-700` | #4F46E5 |
| CTA (action) | `emerald-500`, `emerald-600` | #10B981 |
| Background light | `gray-50`, `white` | #F9FAFB |
| Text primary | `gray-900` | #111827 |
| Text secondary | `gray-600` | #4B5563 |

## Responsive Breakpoints

| Breakpoint | Prefix | Typical Use |
|---|---|---|
| Default (mobile) | none | Base styles |
| sm | `sm:` | 640px+ |
| md | `md:` | 768px+ — tablet landscape |
| lg | `lg:` | 1024px+ — desktop |
| xl | `xl:` | 1280px+ — large desktop |

## Copywriting Cheat Sheet

### CTA Button Verbs (Active Acquisition)
- "Comenzar mi prueba gratis"
- "Obtener mi demo gratuita"
- "Empezar a ahorrar tiempo"
- "Reservar mi llamada"
- "Unirme a la lista de espera"

### Words to Avoid
- "Enviar" → passive, no value communicated
- "Registrarse" → implies commitment, not benefit
- "Más información" → vague, low urgency
- "Click aquí" → outdated, no context

### Power Words for Headlines
- Automatiza, Elimina, Recupera, Ahorra, Consigue
- Sin esfuerzo, En minutos, Desde el día uno
