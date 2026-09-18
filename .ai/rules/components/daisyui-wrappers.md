# DaisyUI Components & Mandatory Project Wrappers

## Rule Summary
1. **Always check DaisyUI 5 first**: Before writing custom CSS or composing long strings of Tailwind utility classes for UI elements (buttons, inputs, cards, badges, alerts, modals, dropdowns, tables, drawers, etc.), use the corresponding DaisyUI component.
2. **STRICTLY PROHIBITED**: Inlining raw DaisyUI classes (such as `class="btn btn-primary"`, `class="card bg-base-100"`, `class="input input-bordered"`) directly into page views or Livewire templates.
3. **MANDATORY**: Every DaisyUI component must be wrapped inside a project Blade component under `resources/views/components/` (e.g. `<x-button>`, `<x-input>`, `<x-card>`, `<x-badge>`, `<x-alert>`, `<x-modal>`).
4. **Lifecycle**: If a wrapper component does not exist for the component you need, create the wrapper under `resources/views/components/` first, ensure mobile ergonomics (`min-h-[44px]`, tactile active states), and then consume it in the view.

## Required Wrapper Anatomy
- Use `@props([...])` for typed or defaulted configuration (e.g., `variant`, `size`, `type`).
- Use `$attributes->class([...])` to allow caller overrides and preserve HTML/Alpine/Livewire attributes (`wire:model`, `wire:click`, etc.).
- Maintain mobile ergonomics: interactive elements must respect a minimum touch target size of 44px (`min-h-[44px]`) and tactile touch feedback (`active:scale-95`).

## Examples

### ❌ Anti-Pattern (Do NOT do this in views)
```blade
<button type="submit" class="btn btn-primary min-h-[44px]">Guardar</button>
<div class="card bg-zinc-900 border border-zinc-800 p-6">...</div>
```

### ✅ Compliant Pattern (Do this)
```blade
<x-button variant="primary" type="submit">Guardar</x-button>
<x-card>...</x-card>
```
