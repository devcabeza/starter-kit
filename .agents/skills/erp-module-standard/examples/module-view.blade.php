<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    {{-- Feedback Messages --}}
    @if (session('success'))
        <x-alert variant="success">{{ session('success') }}</x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="danger">{{ session('error') }}</x-alert>
    @endif

    {{-- ========================================== --}}
    {{-- PILLAR 2 & 3: STORE & EDIT VIEW            --}}
    {{-- ========================================== --}}
    @if ($view === 'store' || $view === 'edit')
        <div class="space-y-6">
            <x-card :title="$view === 'edit' ? 'Editar Registro' : 'Crear Nuevo Registro'">
                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input
                            wire:model="name"
                            label="Nombre"
                            placeholder="Nombre del registro"
                            error="{{ $errors->first('name') }}"
                        />

                        <x-input
                            wire:model="code"
                            label="Código / Identificador"
                            placeholder="Ej. COD-001"
                            error="{{ $errors->first('code') }}"
                        />
                    </div>

                    <x-input
                        wire:model="description"
                        label="Descripción"
                        placeholder="Detalles u observaciones..."
                        error="{{ $errors->first('description') }}"
                    />

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-zinc-800">
                        <x-button wire:click="cancel" variant="ghost" type="button">
                            Cancelar
                        </x-button>
                        <x-button variant="primary" type="submit">
                            {{ $view === 'edit' ? 'Guardar Cambios' : 'Crear Registro' }}
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

    {{-- ========================================== --}}
    {{-- PILLAR 4: SHOW VIEW (OPERATIONAL DETAIL)   --}}
    {{-- ========================================== --}}
    @elseif ($view === 'show' && $selectedRecord)
        <div class="space-y-6 animate-in fade-in duration-150">
            <x-card class="bg-zinc-900 border-zinc-800 shadow-2xl">
                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-800 gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-mono text-cyan-400 font-bold uppercase">Detalle Operativo</span>
                            <x-badge :variant="$selectedRecord->is_active ? 'success' : 'neutral'" size="xs">
                                {{ $selectedRecord->is_active ? 'Activo' : 'Inactivo' }}
                            </x-badge>
                        </div>
                        <h2 class="text-2xl font-black text-white mt-1">{{ $selectedRecord->name }}</h2>
                        <span class="text-xs text-zinc-500 font-mono">{{ $selectedRecord->code }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <x-button wire:click="edit({{ $selectedRecord->id }})" variant="neutral" size="sm">
                            Editar
                        </x-button>
                        <x-button wire:click="closeDetail" variant="ghost" size="sm">
                            ✕ Volver al Listado
                        </x-button>
                    </div>
                </div>

                {{-- Metric Cards (2 to 4 KPIs) --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 py-4">
                    <div class="p-3.5 rounded-2xl bg-zinc-950/80 border border-zinc-800 space-y-1">
                        <span class="text-[11px] uppercase tracking-wider text-zinc-400 font-semibold">Total Operaciones</span>
                        <div class="text-2xl font-bold text-white font-mono">0</div>
                    </div>
                </div>

                {{-- Related Sub-records Table --}}
                <div class="pt-4">
                    <h3 class="text-sm font-bold text-zinc-200 mb-3">Historial / Elementos Relacionados</h3>
                    <div class="overflow-x-auto rounded-xl border border-zinc-800 bg-zinc-950/60 p-6 text-center text-sm text-zinc-500">
                        Detalles operativos específicos del registro.
                    </div>
                </div>
            </x-card>
        </div>

    {{-- ========================================== --}}
    {{-- PILLAR 1: LIST VIEW (DEFAULT OVERVIEW)     --}}
    {{-- ========================================== --}}
    @else
        <div class="space-y-6">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-800 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Módulo de Gestión</h1>
                    <p class="text-sm text-zinc-400">Listado general, filtros y control operativo.</p>
                </div>
                <x-button wire:click="create" variant="primary">
                    + Nuevo Registro
                </x-button>
            </div>

            {{-- Search & Filters --}}
            <div class="flex flex-wrap items-center gap-4">
                <x-input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre..."
                    class="w-full sm:w-80"
                />
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto rounded-2xl border border-zinc-800 bg-zinc-900/60">
                <table class="w-full text-left text-sm">
                    <thead class="bg-zinc-800/60 text-zinc-400 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="p-3.5">Código</th>
                            <th class="p-3.5">Nombre</th>
                            <th class="p-3.5">Estado</th>
                            <th class="p-3.5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/60 text-zinc-300">
                        @forelse ($records as $item)
                            <tr class="hover:bg-zinc-800/30 transition-colors">
                                <td class="p-3.5 font-mono text-xs text-zinc-400">{{ $item->code }}</td>
                                <td class="p-3.5 font-semibold text-white">{{ $item->name }}</td>
                                <td class="p-3.5">
                                    <x-badge :variant="$item->is_active ? 'success' : 'neutral'" size="xs">
                                        {{ $item->is_active ? 'Activo' : 'Inactivo' }}
                                    </x-badge>
                                </td>
                                <td class="p-3.5 text-right space-x-2">
                                    <x-button wire:click="show({{ $item->id }})" variant="neutral" size="xs">
                                        Ver Detalle
                                    </x-button>
                                    <x-button wire:click="edit({{ $item->id }})" variant="ghost" size="xs">
                                        Editar
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-zinc-500 text-sm">
                                    No se encontraron registros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $records->links() }}
            </div>
        </div>
    @endif
</div>
