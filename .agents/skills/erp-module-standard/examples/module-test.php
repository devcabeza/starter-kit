<?php

declare(strict_types=1);

use App\Livewire\Examples\ExampleManager;
use App\Models\ExampleModel;
use App\Models\Organization;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->org = Organization::factory()->create();
    $this->user = User::factory()->create(['current_organization_id' => $this->org->id]);
    $this->actingAs($this->user);
});

// Pillar 1: LIST
it('renders list with paginated records scoped to tenant', function () {
    ExampleModel::factory()->count(3)->create(['organization_id' => $this->org->id]);

    $otherOrg = Organization::factory()->create();
    ExampleModel::factory()->create(['organization_id' => $otherOrg->id, 'name' => 'Foreign Record']);

    Livewire::test(ExampleManager::class)
        ->assertOk()
        ->assertSet('view', 'list')
        ->assertDontSee('Foreign Record');
});

// Pillar 2: STORE
it('can store a new record with tenant validation', function () {
    Livewire::test(ExampleManager::class)
        ->call('create')
        ->assertSet('view', 'store')
        ->set('name', 'Nuevo Item')
        ->set('code', 'ITEM-001')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('view', 'list');

    $this->assertDatabaseHas('example_models', [
        'organization_id' => $this->org->id,
        'name' => 'Nuevo Item',
        'code' => 'ITEM-001',
    ]);
});

// Pillar 3: EDIT
it('can edit and update an existing record safely', function () {
    $item = ExampleModel::factory()->create([
        'organization_id' => $this->org->id,
        'name' => 'Original Name',
        'code' => 'ORIG-1',
    ]);

    Livewire::test(ExampleManager::class)
        ->call('edit', $item->id)
        ->assertSet('view', 'edit')
        ->assertSet('editingId', $item->id)
        ->set('name', 'Updated Name')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('view', 'list');

    expect($item->fresh()->name)->toBe('Updated Name');
});

// Pillar 4: SHOW
it('can display the deep operational show view', function () {
    $item = ExampleModel::factory()->create([
        'organization_id' => $this->org->id,
        'name' => 'Specific Detail Item',
    ]);

    Livewire::test(ExampleManager::class)
        ->call('show', $item->id)
        ->assertSet('view', 'show')
        ->assertSet('selectedId', $item->id)
        ->assertSee('Specific Detail Item')
        ->call('closeDetail')
        ->assertSet('view', 'list')
        ->assertSet('selectedId', null);
});
