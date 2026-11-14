---
name: erp-module-standard
description: "MANDATORY skill when creating, modifying, adding, styling, or refactoring ANY view, page, component, or module inside the ERP dashboard (e.g. /dashboard, /clients, /orders, /routes, /inventory, /dispatches, /services, /team, /roles, /billing, or any new dashboard module). Strictly enforces the 4-page RESTful architecture for all dashboard modules: list (paginated index), create/store (creation form), show (operational detail view with KPIs), and edit (preloaded update form). MUST be triggered whenever the user or agent mentions 'crear vista', 'nueva vista', 'crear modulo', 'nuevo modulo', 'pantalla en el dashboard', 'dashboard view', 'modulo del dashboard', 'agregar vista', or wants to add any page or screen within the dashboard."
license: MIT
metadata:
  author: fillr
---

# ERP Dashboard Module Standard (4 Dedicated Pages: List, Store, Edit, Show)

**MANDATORY:** Every functional module, screen, or view inside the Fillr ERP dashboard MUST strictly follow the 4-page architecture with dedicated browser URLs and dedicated Livewire components.

```
╔═══════════════════════════════════════════════════════════════════════════════════════╗
║                      THE 4-PAGE ERP ROUTE & COMPONENT STANDARD                        ║
║                                                                                       ║
║  1. LIST   → GET /{module}            → {Module}List   (index, filters, search)       ║
║  2. STORE  → GET /{module}/create     → {Module}Create (creation form & validation)   ║
║  3. SHOW   → GET /{module}/{id}       → {Module}Show   (detail view, KPIs, sub-tables)║
║  4. EDIT   → GET /{module}/{id}/edit  → {Module}Edit   (preloaded update form)        ║
╚═══════════════════════════════════════════════════════════════════════════════════════╝
```

---

## ⚠️ Mandatory Rule: Creating ANY View in the Dashboard

When an agent is asked to **create a view**, **add a screen**, or **build a new module** within the ERP dashboard:

1. **Never create isolated or ad-hoc views**: Do not build a standalone view or ad-hoc route without structuring the module into the canonical 4 pillars.
2. **Every module must have all 4 dedicated pages**:
   - Even if the request initially emphasizes only a table or only a form, the module MUST be scaffolded with its 4 RESTful routes and Livewire components: `List`, `Create`, `Show`, and `Edit`.
3. **Dedicated URLs**: Every pillar must have its own browser URL for deep linking, bookmarks, and standard browser history.
4. **Tenant-Safe**: Every query, create, update, and delete MUST strictly enforce `organization_id` scoping via `TenantContextInterface`.

---

## 1. Route Matrix per Module

For any module `{resource}` (e.g. `clients`, `routes`, `orders`, `inventory`, `dispatches`, `services`, `team`, `roles`, or any new module):

```php
// routes/web/erp.php
Route::prefix('{resource}')->name('{resource}.')->group(function () {
    Route::get('/', {Resource}List::class)->name('index');
    Route::get('/create', {Resource}Create::class)->name('create');
    Route::get('/{{model}}', {Resource}Show::class)->name('show');
    Route::get('/{{model}}/edit', {Resource}Edit::class)->name('edit');
});

// Backward-compatible alias for sidebar navigation & tests
Route::get('/{resource}', {Resource}List::class)->name('{resource}');
```

---

## 2. Pillars & Components Specification

### 2.1 Pillar 1: `list` (`{Resource}List.php`)
- **Route**: `GET /{module}` (`route('{module}')` or `route('{module}.index')`)
- **Livewire Component**: `App\Livewire\{Module}\{Resource}List`
- **Blade Template**: `resources/views/livewire/{module}/{resource}-list.blade.php`
- **Mandatory Elements**:
  - Real-time search input with debounce (`wire:model.live.debounce.300ms="search"`).
  - Filter dropdowns and pagination (`WithPagination` trait, 15 records per page).
  - Scoped to active tenant: `Model::where('organization_id', $orgId)`.
  - Header button: `<x-button href="{{ route('{module}.create') }}" variant="primary">+ Nuevo [Recurso]</x-button>`.
  - Table row actions:
    - `<x-button href="{{ route('{module}.show', $item) }}" variant="neutral" size="xs">Ver Detalle</x-button>`
    - `<x-button href="{{ route('{module}.edit', $item) }}" variant="ghost" size="xs">Editar</x-button>`

### 2.2 Pillar 2: `store` / `create` (`{Resource}Create.php`)
- **Route**: `GET /{module}/create` (`route('{module}.create')`)
- **Livewire Component**: `App\Livewire\{Module}\{Resource}Create`
- **Blade Template**: `resources/views/livewire/{module}/{resource}-create.blade.php`
- **Mandatory Elements**:
  - Full-page card with title "Crear Nuevo [Recurso]".
  - Form inputs using DaisyUI wrapper components (`<x-input>`, `<x-select>`) with validation error feedback.
  - Multi-tenancy: Injects `TenantContextInterface` and forces `'organization_id' => $org->id`.
  - Actions:
    - `Cancelar`: Link to `route('{module}')`.
    - `Guardar / Crear`: Submits form, flashes `session()->flash('success', '...')` and redirects to `route('{module}.show', $newRecord)` or `route('{module}')`.

### 2.3 Pillar 3: `show` (`{Resource}Show.php`)
- **Route**: `GET /{module}/{id}` (`route('{module}.show', $id)`)
- **Livewire Component**: `App\Livewire\{Module}\{Resource}Show`
- **Blade Template**: `resources/views/livewire/{module}/{resource}-show.blade.php`
- **Mandatory Elements**:
  - Preloads record ensuring tenant ownership:
    ```php
    public function mount(Model $model, TenantContextInterface $tenantContext): void
    {
        $org = $tenantContext->getTenant() ?? auth()->user()?->currentOrganization;
        if ($model->organization_id !== $org?->id) {
            abort(404);
        }
        $this->model = $model;
    }
    ```
  - Header with record code/name, badges, button `Editar` (`route('{module}.edit', $model)`), and `✕ Volver al Listado` (`route('{module}')`).
  - 2 to 4 KPI metric cards relevant to the entity.
  - Sub-records table, history log, or interactive map.

### 2.4 Pillar 4: `edit` (`{Resource}Edit.php`)
- **Route**: `GET /{module}/{id}/edit` (`route('{module}.edit', $id)`)
- **Livewire Component**: `App\Livewire\{Module}\{Resource}Edit`
- **Blade Template**: `resources/views/livewire/{module}/{resource}-edit.blade.php`
- **Mandatory Elements**:
  - Loads model verifying tenant isolation: abort(404) if foreign organization.
  - Mounts current values to properties.
  - Updates model upon submission and redirects to `route('{module}.show', $model)` with `session()->flash('success', '...')`.
  - `Cancelar` button returns cleanly to `route('{module}.show', $model)` or `route('{module}')`.

---

## 3. Directory & Rules Structure

- [`rules/four-pillars.md`](./rules/four-pillars.md): Detailed specifications for each of the 4 pillars.
- [`rules/livewire-spa-pattern.md`](./rules/livewire-spa-pattern.md): State management, methods, and lifecycle hooks.
- [`rules/multitenancy-safety.md`](./rules/multitenancy-safety.md): Data scoping, authorization policies, and security checks.
- [`examples/module-template.php`](./examples/module-template.php): Canonical Livewire component template.
- [`examples/module-view.blade.php`](./examples/module-view.blade.php): Canonical Blade view template.
- [`examples/module-test.php`](./examples/module-test.php): Canonical Pest test suite template.
