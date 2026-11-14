@props([
    'href' => null,
    'action' => null,
    'method' => 'POST',
    'icon' => null,
    'variant' => 'default',
    'type' => 'button',
])

@php
$variantClasses = match ($variant) {
    'danger', 'error' => 'text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 focus:bg-rose-500/10 active:bg-rose-500/20',
    default => 'text-zinc-200 hover:text-white hover:bg-zinc-800/70 focus:bg-zinc-800/70 active:bg-zinc-800',
};

$itemClasses = 'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors w-full min-h-[44px] cursor-pointer select-none active:scale-[0.98]';
@endphp

<li>
    @if ($action)
        <form method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}" action="{{ $action }}" class="w-full p-0 m-0 bg-transparent hover:bg-transparent focus:bg-transparent">
            @csrf
            @if (! in_array(strtoupper($method), ['GET', 'POST']))
                @method($method)
            @endif
            <button type="submit" {{ $attributes->class([$itemClasses, $variantClasses, 'w-full text-left']) }}>
                @if ($icon)
                    <span class="w-5 h-5 shrink-0 flex items-center justify-center">
                        {{ $icon }}
                    </span>
                @endif
                <span class="flex-1">{{ $slot }}</span>
            </button>
        </form>
    @elseif ($href)
        <a href="{{ $href }}" {{ $attributes->class([$itemClasses, $variantClasses]) }}>
            @if ($icon)
                <span class="w-5 h-5 shrink-0 flex items-center justify-center">
                    {{ $icon }}
                </span>
            @endif
            <span class="flex-1">{{ $slot }}</span>
        </a>
    @else
        <button type="{{ $type }}" {{ $attributes->class([$itemClasses, $variantClasses]) }}>
            @if ($icon)
                <span class="w-5 h-5 shrink-0 flex items-center justify-center">
                    {{ $icon }}
                </span>
            @endif
            <span class="flex-1 text-left">{{ $slot }}</span>
        </button>
    @endif
</li>
