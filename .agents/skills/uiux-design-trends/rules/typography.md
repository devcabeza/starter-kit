# Kinetic Typography & Variable Fonts

## Core Concept

Dynamic typography that responds to user interaction using variable fonts.

## Variable Font Setup

```blade
{{-- Add to head --}}
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap">

{{-- Or use Tailwind --}}
<style>
    .font-variable {
        font-variation-settings: 'wght' 300;
    }
</style>
```

## Scroll-Based Font Weight

```blade
<div 
    x-data="{ 
        scrollY: 0,
        get fontWeight() {
            return Math.min(900, 300 + (this.scrollY / 5));
        }
    }"
    x-on:scroll.window="scrollY = window.scrollY"
    :style="`font-variation-settings: 'wght' ${fontWeight}`"
    class="text-6xl font-variable"
>
    Dynamic Heading
</div>
```

## Hover-Based Typography

```blade
<div 
    x-data="{ 
        isHovered: false,
        get fontWeight() {
            return this.isHovered ? 800 : 400;
        }
    }"
    x-on:mouseenter="isHovered = true"
    x-on:mouseleave="isHovered = false"
    :style="`font-variation-settings: 'wght' ${fontWeight}`"
    class="text-4xl font-variable transition-all duration-300"
>
    Hover Me
</div>
```

## Responsive Typography

```blade
{{-- Mobile-first typography --}}
<h1 class="
    text-3xl 
    sm:text-4xl 
    md:text-5xl 
    lg:text-6xl
    font-bold tracking-tight
    leading-tight
">
    Responsive Heading
</h1>

{{-- Line height for readability --}}
<p class="
    text-base md:text-lg
    leading-relaxed md:leading-loose
    max-w-prose
">
    Body text with optimal line length (60-75 characters).
</p>
```

## Font Stack

```javascript
// tailwind.config.js
module.exports = {
    theme: {
        fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            display: ['Inter', 'system-ui', 'sans-serif'],
            mono: ['JetBrains Mono', 'monospace'],
        },
    },
}
```

## Best Practices

1. **One variable font** - Use Inter or similar for all weights
2. **Optimal line length** - 60-75 characters per line
3. **Hierarchy** - Clear distinction between headings and body
4. **Responsive** - Scale typography with viewport
5. **Performance** - Variable fonts reduce HTTP requests
