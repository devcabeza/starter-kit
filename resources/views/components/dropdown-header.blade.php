@props([])

<li {{ $attributes->class(['menu-title px-3 py-2 text-zinc-400 select-none']) }}>
    {{ $slot }}
</li>
