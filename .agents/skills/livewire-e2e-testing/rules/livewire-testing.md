# Livewire Component Testing

## Basic Component Test

```php
<?php
// tests/Feature/Livewire/CounterTest.php

declare(strict_types=1);

use App\Livewire\Counter;
use Livewire\Livewire;

it('renders correctly', function () {
    Livewire::test(Counter::class)
        ->assertSee('Count: 0');
});

it('increments counter', function () {
    Livewire::test(Counter::class)
        ->call('increment')
        ->assertSee('Count: 1');
});

it('decrements counter', function () {
    Livewire::test(Counter::class)
        ->set('count', 5)
        ->call('decrement')
        ->assertSee('Count: 4');
});

it('resets counter', function () {
    Livewire::test(Counter::class)
        ->set('count', 10)
        ->call('reset')
        ->assertSee('Count: 0');
});
```

## Form Component Test

```php
<?php
// tests/Feature/Livewire/CreateUserTest.php

declare(strict_types=1);

use App\Livewire\CreateUser;
use App\Models\User;
use Livewire\Livewire;

it('renders form correctly', function () {
    Livewire::test(CreateUser::class)
        ->assertSee('Create User')
        ->assertSee('Name')
        ->assertSee('Email');
});

it('validates name is required', function () {
    Livewire::test(CreateUser::class)
        ->fill(['email' => 'john@example.com'])
        ->call('submit')
        ->assertHasErrors(['name' => 'required']);
});

it('validates email is required', function () {
    Livewire::test(CreateUser::class)
        ->fill(['name' => 'John'])
        ->call('submit')
        ->assertHasErrors(['email' => 'required']);
});

it('validates email format', function () {
    Livewire::test(CreateUser::class)
        ->fill(['email' => 'not-an-email'])
        ->call('submit')
        ->assertHasErrors(['email' => 'email']);
});

it('creates user with valid data', function () {
    Livewire::test(CreateUser::class)
        ->fill([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ])
        ->call('submit')
        ->assertHasNoErrors(['name', 'email']);
    
    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

it('emits event after creation', function () {
    Livewire::test(CreateUser::class)
        ->fill([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ])
        ->call('submit')
        ->assertEmitted('user.created');
});
```

## Modal Component Test

```php
<?php
// tests/Feature/Livewire/ModalTest.php

declare(strict_types=1);

use App\Livewire\ConfirmModal;
use Livewire\Livewire;

it('is closed by default', function () {
    Livewire::test(ConfirmModal::class)
        ->assertDontSee('Confirm Action');
});

it('opens on trigger', function () {
    Livewire::test(ConfirmModal::class)
        ->call('open')
        ->assertSee('Confirm Action');
});

it('closes on cancel', function () {
    Livewire::test(ConfirmModal::class)
        ->call('open')
        ->call('cancel')
        ->assertDontSee('Confirm Action');
});

it('emits event on confirm', function () {
    Livewire::test(ConfirmModal::class)
        ->call('open')
        ->call('confirm')
        ->assertEmitted('confirmed');
});
```

## Table Component Test

```php
<?php
// tests/Feature/Livewire/UserTableTest.php

declare(strict_types=1);

use App\Livewire\UserTable;
use App\Models\User;
use Livewire\Livewire;

it('displays users', function () {
    User::factory()->count(3)->create();
    
    Livewire::test(UserTable::class)
        ->assertSee(User::first()->name);
});

it('filters by search term', function () {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);
    
    Livewire::test(UserTable::class)
        ->set('search', 'John')
        ->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});

it('paginates results', function () {
    User::factory()->count(25)->create();
    
    Livewire::test(UserTable::class)
        ->assertSee(User::first()->name);
});
```

## Available Assertions

| Assertion | Purpose |
|-----------|---------|
| `assertSee($value)` | Value is rendered |
| `assertDontSee($value)` | Value is not rendered |
| `assertSeeHtml($value)` | HTML is rendered |
| `assertHasErrors($fields)` | Validation errors exist |
| `assertHasNoErrors($fields)` | No validation errors |
| `assertEmitted($event)` | Event was emitted |
| `assertNotEmitted($event)` | Event was not emitted |
| `assertSet($property, $value)` | Property has value |
| `assertExpiredFlash($key)` | Flash message exists |

## Livewire Test Helpers

```php
// Set property
->set('property', 'value')

// Call method
->call('methodName')

// Call method with arguments
->call('delete', $id)

// Simulate file upload
->upload('file', '/path/to/file')

// Assert component is not renders
->assertDontRender();
```
