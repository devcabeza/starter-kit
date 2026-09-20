@props([
    'bordered' => true,
])

@php
$borderClass = $bordered ? 'border-t border-zinc-800' : '';
@endphp

<nav {{ $attributes->class(['btm-nav fixed bottom-0 left-0 right-0 z-40 bg-zinc-950/95 backdrop-blur-md pb-[env(safe-area-inset-bottom,0px)] shadow-lg min-h-[56px]', $borderClass]) }}>
    {{ $slot }}
</nav>
