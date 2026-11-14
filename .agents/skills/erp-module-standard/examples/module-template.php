<?php

declare(strict_types=1);

namespace App\Livewire\Examples;

use App\Models\ExampleModel;
use App\Ports\Out\Tenancy\TenantContextInterface;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Módulo Ejemplo | Fillr ERP')]
class ExampleManager extends Component
{
    use WithPagination;

    // View State: 'list' | 'store' | 'edit' | 'show'
    public string $view = 'list';

    // Filters & Search (List)
    public string $search = '';
    public ?string $statusFilter = null;

    // Form fields (Store & Edit)
    public ?int $editingId = null;
    public string $name = '';
    public string $code = '';
    public string $description = '';
    public bool $is_active = true;

    // Detail state (Show)
    public ?int $selectedId = null;

    // --- 1. LIST ACTIONS & NAVIGATION ---

    public function create(): void
    {
        $this->resetForm();
        $this->view = 'store';
    }

    public function edit(int $id, TenantContextInterface $tenantContext): void
    {
        $org = $tenantContext->getTenant() ?? auth()->user()?->currentOrganization;
        $record = ExampleModel::where('organization_id', $org->id)->findOrFail($id);

        $this->resetForm();
        $this->editingId = $record->id;
        $this->name = $record->name;
        $this->code = $record->code;
        $this->description = $record->description ?? '';
        $this->is_active = (bool) $record->is_active;

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

    // --- 2. STORE & EDIT PERSISTENCE ---

    public function save(TenantContextInterface $tenantContext): void
    {
        $org = $tenantContext->getTenant() ?? auth()->user()?->currentOrganization;
        if ($org === null) {
            session()->flash('error', 'Organización no disponible.');
            return;
        }

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('example_models', 'code')
                    ->where('organization_id', $org->id)
                    ->ignore($this->editingId),
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($this->view === 'edit' && $this->editingId !== null) {
            $record = ExampleModel::where('organization_id', $org->id)->findOrFail($this->editingId);
            $record->update($validated);
            session()->flash('success', 'Registro actualizado exitosamente.');
        } else {
            $validated['organization_id'] = $org->id;
            ExampleModel::create($validated);
            session()->flash('success', 'Nuevo registro creado exitosamente.');
        }

        $this->cancel();
    }

    // --- 3. DELETE / DEACTIVATE ---

    public function delete(int $id, TenantContextInterface $tenantContext): void
    {
        $org = $tenantContext->getTenant() ?? auth()->user()?->currentOrganization;
        $record = ExampleModel::where('organization_id', $org->id)->findOrFail($id);
        $record->delete();

        session()->flash('success', 'Registro eliminado correctamente.');
        $this->cancel();
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->name = '';
        $this->code = '';
        $this->description = '';
        $this->is_active = true;
    }

    // --- 4. RENDER (DISPATCHING VIEW DATA) ---

    public function render(TenantContextInterface $tenantContext): View
    {
        $org = $tenantContext->getTenant() ?? auth()->user()?->currentOrganization;

        $records = ExampleModel::where('organization_id', $org->id)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter !== null, fn ($q) => $q->where('is_active', $this->statusFilter === 'active'))
            ->latest()
            ->paginate(15);

        $selectedRecord = null;
        if ($this->selectedId !== null) {
            $selectedRecord = ExampleModel::where('organization_id', $org->id)
                ->with(['relatedItems'])
                ->find($this->selectedId);
        }

        return view('livewire.examples.example-manager', [
            'records' => $records,
            'selectedRecord' => $selectedRecord,
        ]);
    }
}
