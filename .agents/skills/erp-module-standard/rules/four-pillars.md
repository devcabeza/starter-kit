# Detailed Specifications: The 4 Pillars

## Pillar 1: `list` (Listado y Exploración)
- **Component Requirements**:
  - Implement `WithPagination` trait.
  - Bind search query `$search` with `wire:model.live.debounce.300ms="search"`.
  - Provide filter dropdowns for statuses or categories when applicable.
  - Include quick pagination controls at the bottom (`{{ $records->links() }}`).
- **View Requirements**:
  - Header: Section title, description, and prominent `+ Nuevo [Recurso]` button.
  - Table: Responsive wrapper, headers uppercase text-xs text-zinc-400.
  - Status: Badges using `<x-badge>` (`success`, `warning`, `error`, `neutral`).
  - Actions: Three distinct actions per row:
    - `Ver Detalle`: Calls `show($id)`.
    - `Editar`: Calls `edit($id)`.
    - `Eliminar`: Danger button with confirmation or soft-deactivate.
  - Empty state: Informative alert/card guiding user when count is 0.

## Pillar 2: `store` (Creación / Registro)
- **Component Requirements**:
  - Activated by `create()`. Sets `$view = 'store'`, resets form properties and error bag.
  - Explicit validation rules matching domain rules.
  - Injects `TenantContextInterface` to retrieve active tenant ID.
  - Automatically associates `organization_id` with active tenant.
  - Emits/flashes success notification: `session()->flash('success', '...')`.
- **View Requirements**:
  - Card container with title "Crear Nuevo [Recurso]".
  - Inputs with explicit labels and error displays: `<x-input wire:model="fieldName" ... error="{{ $errors->first('fieldName') }}" />`.
  - Actions bar at bottom right: `Cancelar` (calls `cancel()`) and `Guardar` (submits form).

## Pillar 3: `edit` (Edición / Modificación)
- **Component Requirements**:
  - Activated by `edit(int $id)`. Sets `$view = 'edit'`, sets `$editingId = $id`.
  - Loads model verifying tenant isolation: `Model::where('organization_id', $org->id)->findOrFail($id)`.
  - Populates form properties with existing record data.
  - Uses `Rule::unique()->ignore($this->editingId)` for unique columns.
  - Dispatches success message and redirects to `list` or `show`.
- **View Requirements**:
  - Card container with title "Editar [Recurso]: {Nombre}".
  - Reuses same structured input layout as `store`.
  - Actions bar: `Cancelar` and `Guardar Cambios`.

## Pillar 4: `show` (Detalle Operativo / Ficha Profunda)
- **Component Requirements**:
  - Activated by `show(int $id)` (or `viewDetail(int $id)`). Sets `$view = 'show'`, `$selectedId = $id`.
  - Preloads necessary relationships (e.g. `with(['items', 'orders', 'clients'])`).
- **View Requirements**:
  - Header: Record title, code/ID badge, status badge, and navigation actions:
    - Primary contextual action (e.g. `Editar`, `Imprimir`, `Procesar`).
    - Return button: `✕ Volver al Listado` calling `closeDetail()`.
  - KPI Metrics Grid: 2 to 4 high-value numbers relevant to the record.
  - Sub-resource Tabs/Table: Complete list of child records (e.g. items, stops, order logs).
  - Geolocation Map: If record has coordinates (client, route, dispatch), render interactive Leaflet map.
