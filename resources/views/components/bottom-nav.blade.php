@props([
    'bordered' => true,
])

@php
$borderClass = $bordered ? 'border-t border-zinc-800' : '';
@endphp

<nav {{ $attributes->class(['dock fixed bottom-0 left-0 right-0 z-40 bg-zinc-950/95 backdrop-blur-md shadow-lg border-t border-zinc-800/80', $borderClass]) }}>
    {{ $slot }}
</nav>
