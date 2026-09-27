{{--
    Mobile-First Responsive List Component
    Transforms from a stacked touch card list on mobile to an expanded data table on desktop.
    Solves the mobile horizontal-scroll table anti-pattern for Capacitor APK.
--}}

@props([
    'items' => [
        ['id' => 1, 'name' => 'Factura #1082', 'customer' => 'Tech Corp', 'amount' => '$1,250.00', 'status' => 'Pagada'],
        ['id' => 2, 'name' => 'Factura #1083', 'customer' => 'Design Studio', 'amount' => '$450.00', 'status' => 'Pendiente'],
        ['id' => 3, 'name' => 'Factura #1084', 'customer' => 'Global Logistics', 'amount' => '$3,800.00', 'status' => 'En proceso'],
    ]
])

<div class="w-full">
    {{-- Mobile View: Stacked Cards (< md) --}}
    <div class="block md:hidden space-y-3">
        @foreach($items as $item)
            <div class="bg-white dark:bg-zinc-900 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-xs flex flex-col gap-3 active:bg-zinc-50 dark:active:bg-zinc-800/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ $item['customer'] }}
                        </span>
                        <h4 class="text-base font-bold text-zinc-900 dark:text-white mt-0.5">
                            {{ $item['name'] }}
                        </h4>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item['status'] === 'Pagada' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400' }}">
                        {{ $item['status'] }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800">
                    <span class="text-lg font-extrabold text-zinc-900 dark:text-white">
                        {{ $item['amount'] }}
                    </span>

                    <button type="button"
                            class="min-h-[44px] min-w-[44px] px-3 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 text-sm font-medium rounded-xl inline-flex items-center justify-center gap-1.5 active:scale-95 transition-transform">
                        <span>Ver</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Desktop View: Structured Table (>= md) --}}
    <div class="hidden md:block overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-xs">
        <table class="w-full text-left border-collapse">
            <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800 text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4">Item</th>
                    <th scope="col" class="px-6 py-4">Cliente</th>
                    <th scope="col" class="px-6 py-4">Monto</th>
                    <th scope="col" class="px-6 py-4">Estado</th>
                    <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                @foreach($items as $item)
                    <tr class="hover:bg-zinc-50/75 dark:hover:bg-zinc-800/40 transition-colors">
                        <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">{{ $item['name'] }}</td>
                        <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $item['customer'] }}</td>
                        <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">{{ $item['amount'] }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item['status'] === 'Pagada' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400' }}">
                                {{ $item['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-semibold text-sm">
                                Gestionar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
