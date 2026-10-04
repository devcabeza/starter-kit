---
name: mobile-capacitor-views
description: "MANDATORY skill when creating, modifying, or refactoring ANY Blade view, Livewire component, layout, or frontend UI. Enforces strict mobile-first responsiveness, native app feel, and deep optimization for Capacitor Android/iOS APKs and WebViews. Covers viewport settings, safe area insets (notches/system bars), touch target ergonomics (minimum 44-48px), thumb-zone layouts, virtual keyboard adaptation, bottom navigation/drawers instead of desktop patterns, responsive card replacements for data tables, and Livewire performance in mobile WebViews."
license: MIT
metadata:
  author: starter-kit
---

# Mobile-First & Capacitor APK Views Development

**MANDATORY:** In this project, mobile views are compiled and distributed as native Android (and iOS) APKs via Capacitor. Every single Blade view, Livewire component, and UI layout MUST be designed mobile-first, ensuring an authentic native mobile application feel inside the Capacitor WebView before scaling up to tablet and desktop.

---

## 1. The Capacitor APK & WebView Reality

When running inside a Capacitor APK, web content is rendered inside an Android/iOS WebView. This introduces strict constraints distinct from a regular desktop browser:

### 1.1 Viewport Configuration
Every layout must contain `viewport-fit=cover` in the viewport meta tag so content correctly handles hardware notches and display cutouts:
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
```

### 1.2 System Bars & Safe Areas (Notches & Gesture Bars)
On modern mobile devices, the top has a status bar (with camera punch-holes or notches) and the bottom has an OS gesture navigation bar.
- **Top Safe Area**: Protect headers and top bars with `pt-safe` or `pt-[env(safe-area-inset-top)]`.
- **Bottom Safe Area**: Protect bottom navigation, floating buttons, and fixed footers with `pb-safe` or `pb-[calc(1rem+env(safe-area-inset-bottom))]`.
- **Full Viewport Containers**: Use `min-h-screen-safe` or `h-screen-safe` rather than a naive `100vh` or `h-screen` which often gets clipped by mobile navigation bars.

### 1.3 Native Touch Interaction (No Tap Delay & No Callout)
Standard web pages have blue tap highlight boxes and long-press callouts that break native app immersion.
- Use `touch-callout-none` (`-webkit-touch-callout: none; -webkit-tap-highlight-color: transparent;`).
- Set `select-none` on interactive elements (buttons, bottom nav tabs, icons, cards).
- Keep `select-text` exclusively on readable text passages where copying is expected.

---

## 2. Mobile-First Layout Rules

### 2.1 Always Style for Mobile First (<640px)
Write base Tailwind classes without breakpoints for small mobile screens (360px - 414px width). Use `md:` and `lg:` **only** to expand or enrich the layout for desktop:

```blade
{{-- GOOD: Mobile single-column by default, expands to 2 columns on desktop --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 md:p-8">
    ...
</div>

{{-- BAD: Desktop layout forced without mobile default --}}
<div class="flex flex-row p-8">
    ...
</div>
```

### 2.2 Zero Horizontal Scroll (Strict Rule)
Mobile apps must never have horizontal viewport overflow.
- Ensure the root layout container has `overflow-x-hidden` or `w-full max-w-full`.
- Always add `min-w-0` to flex child items to allow text truncation and prevent flex children from stretching past the screen width:
```blade
<div class="flex items-center gap-3 min-w-0">
    <p class="truncate min-w-0 text-sm font-medium">Very long title that will not break screen layout</p>
</div>
```

### 2.3 Data Tables vs Mobile Card Stacks
Never force users to swipe horizontally across a wide desktop `<table>` on mobile.
- Use the **Card-to-Table pattern**:
  - On `< md`: render clean, touchable stacked cards (`block md:hidden`).
  - On `>= md`: render the full data table (`hidden md:table`).
*(See `examples/responsive-card-list.blade.php` for reference).*

---

## 3. Touch Ergonomics & The "Thumb Zone"

Mobile users navigate primarily with one thumb while holding the phone.

```
┌─────────────────────────────────┐
│     Hard to Reach (Headers)     │ ◄── Read-only titles, non-critical back button
├─────────────────────────────────┤
│                                 │
│        Comfortable Reach        │ ◄── Main scrollable content, cards, inputs
│                                 │
├─────────────────────────────────┤
│                                 │ ◄── PRIMARY CTAs, Submit buttons,
│     NATURAL THUMB ZONE (HOT)    │     Floating action buttons (FAB),
│                                 │     Bottom navigation bar
└─────────────────────────────────┘
```

### 3.1 Minimum Touch Targets
- Every interactive element (buttons, icon links, tabs, checkboxes) must have a minimum tap area of **44x44px** (preferably **48x48px** per Android Material / WCAG):
```blade
<button type="button" class="min-h-[48px] min-w-[48px] px-4 py-3 ...">
    Acción
</button>
```

### 3.2 Instant Visual Feedback (Active States)
Mobile screens do not have mouse `:hover` states. Relying on hover causes broken or sticky styles on touch devices.
- Always implement active states: `active:scale-95`, `active:opacity-80`, or `active:bg-zinc-100`.
- Include smooth transition durations: `transition-all duration-100`.

### 3.3 Sticky Bottom Action Bars
For key workflows (checkout, checkout confirmation, form submissions, multi-step wizards), fix the primary action button to the bottom thumb zone:
```blade
<div class="fixed bottom-0 inset-x-0 p-4 pb-safe bg-white/95 dark:bg-zinc-900/95 backdrop-blur border-t border-zinc-200 dark:border-zinc-800 md:static md:p-0 md:bg-transparent md:border-0 z-30">
    <button type="submit" class="w-full min-h-[48px] py-3 bg-indigo-600 text-white font-semibold rounded-xl active:scale-95 transition-transform">
        Guardar Cambios
    </button>
</div>
```

---

## 4. Mobile Forms & Virtual Keyboard Protection

### 4.1 Prevent Auto-Zoom on Input Focus
WebViews (both iOS and Android) automatically zoom into inputs if the computed font size is below 16px.
- **Rule:** Never use `text-xs` or `text-sm` on `<input>`, `<select>`, or `<textarea>`.
- Always use `text-base` (minimum 16px) on form controls:
```blade
<input type="text" class="w-full text-base min-h-[48px] px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 ...">
```

### 4.2 Keyboard Types (`inputmode` & `type`)
Always specify `inputmode` and semantic `type` so the mobile OS triggers the optimal keyboard layout:
- Digits/PINs/Amounts: `inputmode="numeric"` or `inputmode="decimal"`
- Phone numbers: `type="tel"`
- Email: `type="email" autocomplete="email" autocapitalize="none"`
- Search: `type="search"`

### 4.3 Safe Spacing for Virtual Keyboards
When a virtual keyboard opens, screen height drops by 40-50%.
- Ensure form containers have ample bottom padding (`pb-24 md:pb-8`) so inputs and submit buttons do not get hidden underneath the keyboard.

---

## 5. Native App Patterns vs Desktop Anti-Patterns

| Desktop Anti-Pattern on Mobile | Native Mobile Replacement |
|--------------------------------|---------------------------|
| Centered floating modal window | **Bottom Sheet Drawer** (slide up from bottom, see `examples/bottom-sheet.blade.php`) |
| Top wide horizontal navbar | **Bottom Navigation Bar** with icons + labels (see `examples/bottom-nav.blade.php`) |
| Wide multi-column data table | **Stacked Card List** with status badges and touch trigger |
| Tiny 16px dropdown menu | Full-width selector or bottom action sheet |
| Hover tooltips | Explicit helper text or tap-to-expand disclosure |
| Multi-step forms all on one screen | Paginated swipeable cards with step indicator |

---

## 6. Livewire 4 & Alpine.js Mobile Optimization

Mobile devices in WebViews can experience CPU and latency constraints. Apply these patterns to ensure 60fps smoothness:

### 6.1 Instant Loading Indicators (`wire:loading`)
Mobile users demand immediate tactile response. Never leave a tap without immediate loading state:
```blade
<button wire:click="save" type="button" class="relative min-h-[48px] w-full ...">
    <span wire:loading.remove wire:target="save">Guardar</span>
    <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">...</svg>
        Procesando...
    </span>
</button>
```

### 6.2 Throttled & Debounced Livewire Inputs
Typing on mobile virtual keyboards while `wire:model.live` fires network requests causes typing stutter.
- Use `wire:model.live.debounce.300ms` or `wire:model.blur` for text inputs.

### 6.3 Bottom Sheets & Drawers with Alpine.js
Use smooth native-like slide transitions:
```blade
<div x-show="open"
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="translate-y-full"
     x-transition:enter-end="translate-y-0"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="translate-y-0"
     x-transition:leave-end="translate-y-full"
     class="fixed inset-x-0 bottom-0 pb-safe ...">
    ...
</div>
```

---

## 7. Tailwind v4 Safe-Area Utilities Reference

Configured in `resources/css/app.css`:

| Utility Class | CSS Output | Usage |
|---------------|------------|-------|
| `pt-safe` | `padding-top: env(safe-area-inset-top, 0px)` | Headers, status-bar clearance |
| `pb-safe` | `padding-bottom: env(safe-area-inset-bottom, 0px)` | Bottom nav, bottom sheets, sticky footers |
| `pl-safe` | `padding-left: env(safe-area-inset-left, 0px)` | Landscape notch clearance |
| `pr-safe` | `padding-right: env(safe-area-inset-right, 0px)` | Landscape notch clearance |
| `p-safe` | Safe padding on all 4 sides | Fullscreen modals/containers |
| `min-h-screen-safe` | `min-height: calc(100vh - safe insets)` | Fullscreen mobile views without scroll clipping |
| `h-screen-safe` | `height: calc(100vh - safe insets)` | Locked screen height views |
| `touch-callout-none` | `-webkit-touch-callout: none; -webkit-tap-highlight-color: transparent` | Buttons, tabs, cards |

---

## 8. Agent Pre-Flight Checklist

Before completing ANY task that creates or modifies Blade views or Livewire components, the agent MUST verify:

- [ ] **Viewport Fit**: Does the layout have `viewport-fit=cover`?
- [ ] **Safe Areas**: Are headers protected with `pt-safe` and bottom navigation/action buttons with `pb-safe`?
- [ ] **Touch Targets**: Are all interactive elements at least 44x44px (or 48x48px for primary buttons)?
- [ ] **Zero Horizontal Scroll**: Is the page tested against 360px and 390px widths with no horizontal scrollbars?
- [ ] **Thumb Zone Ergonomics**: Are primary submit/action buttons reachable at the bottom of the screen?
- [ ] **Input Font Size**: Are all inputs, selects, and textareas set to `text-base` (minimum 16px) to avoid auto-zoom?
- [ ] **No Desktop Tables on Mobile**: Are tabular data representations converted into cards on `< md` screens?
- [ ] **Active States**: Do all buttons and links have `active:scale-95` or active visual feedback instead of relying on `:hover`?
- [ ] **Modal vs Bottom Sheet**: If a dialog or menu was added, does it display as a bottom sheet on mobile screens?
- [ ] **Livewire Responsiveness**: Are `wire:loading` states provided for every user action?

---

## 9. Bundled Reference Examples

When creating new views or components, inspect and reuse the patterns in this skill's `examples/` directory:
- [Bottom Navigation Bar](file:///home/alejandrocabeza/workspace/starter-kit/.agents/skills/mobile-capacitor-views/examples/bottom-nav.blade.php): Fixed bottom navigation with safe-area spacing and active indicator.
- [Bottom Sheet Drawer](file:///home/alejandrocabeza/workspace/starter-kit/.agents/skills/mobile-capacitor-views/examples/bottom-sheet.blade.php): Slide-up mobile modal with drag pill handle and thumb-zone actions.
- [Responsive Card List](file:///home/alejandrocabeza/workspace/starter-kit/.agents/skills/mobile-capacitor-views/examples/responsive-card-list.blade.php): Adaptive data presentation that switches from mobile touch cards to desktop table.
