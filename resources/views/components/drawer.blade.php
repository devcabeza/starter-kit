@props([
    'id' => 'drawer-' . uniqid(),
])

<div {{ $attributes->class(['drawer']) }}>
    <input id="{{ $id }}" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">
        {{ $slot }}
    </div>
    <div class="drawer-side z-50">
        <label for="{{ $id }}" aria-label="close sidebar" class="drawer-overlay"></label>
        <aside class="menu bg-zinc-900 text-zinc-100 min-h-full w-80 p-4 pt-[env(safe-area-inset-top,1rem)] pb-[env(safe-area-inset-bottom,1rem)] border-r border-zinc-800">
            {{ $sidebar ?? '' }}
        </aside>
    </div>
</div>
