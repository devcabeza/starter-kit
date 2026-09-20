@props([
    'title' => '',
    'arrow' => true,
    'bordered' => true,
    'open' => false,
])

@php
$baseClasses = 'collapse bg-zinc-900/60 transition-all duration-200';
$arrowClass = $arrow ? 'collapse-arrow' : '';
$borderClass = $bordered ? 'border border-zinc-800/80 hover:border-zinc-700' : '';
@endphp

<details {{ $attributes->class([$baseClasses, $arrowClass, $borderClass, 'rounded-2xl']) }} @if($open) open @endif>
    <summary class="collapse-title text-base sm:text-lg font-semibold text-white min-h-[48px] flex items-center cursor-pointer select-none py-4 px-6">
        {{ $title }}
    </summary>
    <div class="collapse-content text-sm sm:text-base text-zinc-400 px-6 pb-5 leading-relaxed select-text">
        {{ $slot }}
    </div>
</details>
