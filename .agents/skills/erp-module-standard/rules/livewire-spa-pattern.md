# Livewire Single-Component SPA Pattern

## State Management Standard

Rather than splitting simple CRUD operations into multiple controllers and multiple full-page reloads, an ERP dashboard module manages state fluidly inside a single Livewire component.

### Canonical State Properties

```php
// Active view mode
public string $view = 'list'; // 'list' | 'store' | 'edit' | 'show'

// Tracking IDs
public ?int $editingId = null;
public ?int $selectedId = null;

// Search & Pagination
public string $search = '';
public ?string $statusFilter = null;
```

### View Transition Methods

```php
public function create(): void
{
    $this->resetForm();
    $this->view = 'store';
}

public function edit(int $id, TenantContextInterface $tenantContext): void
{
    $org = $tenantContext->getTenant() ?? auth()->user()?->currentOrganization;
    $record = Model::where('organization_id', $org->id)->findOrFail($id);

    $this->resetForm();
    $this->editingId = $record->id;
    // Hydrate form properties...

    $this->view = 'edit';
}

public function show(int $id): void
{
    $this->selectedId = $id;
    $this->view = 'show';
}

public function cancel(): void
{
    $this->resetForm();
    $this->view = 'list';
}

public function closeDetail(): void
{
    $this->selectedId = null;
    $this->view = 'list';
}
```

### Resetting Forms Cleanly

Always clear both the form values and any remaining Livewire validation errors:

```php
private function resetForm(): void
{
    $this->resetValidation();
    $this->editingId = null;
    $this->name = '';
    // reset remaining fields...
}
```
