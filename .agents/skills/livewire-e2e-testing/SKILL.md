---
name: livewire-e2e-testing
description: "MANDATORY skill for every Livewire component or Blade view created or modified. This skill ENFORCES end-to-end testing for all UI components. Use when: creating Livewire components, Blade views, forms, modals, navigation, or any user-facing interface. Every interface MUST have corresponding E2E tests before completion."
license: MIT
metadata:
  author: starter-kit
---

# Livewire & Blade E2E Testing

**MANDATORY:** Every Livewire component and Blade view MUST have E2E tests. No exceptions.

## Core Rule

```
╔══════════════════════════════════════════════════════════════╗
║  NO INTERFACE IS COMPLETE WITHOUT E2E TESTS                 ║
║                                                              ║
║  - Creating a Livewire component? → Write tests FIRST        ║
║  - Modifying a Blade view? → Update tests                    ║
║  - Adding a form? → Test submission + validation             ║
║  - Adding a modal? → Test open/close + interactions          ║
╚══════════════════════════════════════════════════════════════╝
```

## What is E2E Testing for UI?

E2E tests simulate real user interactions:
- **Rendering:** Component displays correctly
- **Interaction:** Clicks, typing, navigation work
- **State:** Livewire properties update correctly
- **Validation:** Forms validate and show errors
- **Persistence:** Data saves to database
- **Authorization:** Users see only what they should

## Testing Stack

| Tool | Purpose |
|------|---------|
| **Pest + Laravel HTTP Tests** | Primary testing method |
| **Livewire Testing API** | Component-level testing |
| **Fluent assertions** | Readable test code |
| **Database assertions** | Verify persistence |

## Mandatory Test Coverage

Every interface MUST test:

### 1. Rendering
```php
it('renders correctly', function () {
    $this->get('/dashboard')
        ->assertOk()
        ->assertSee('Dashboard');
});
```

### 2. Livewire Component State
```php
it('increments counter', function () {
    Livewire::test(Counter::class)
        ->assertSee('Count: 0')
        ->call('increment')
        ->assertSee('Count: 1');
});
```

### 3. Form Submission
```php
it('creates user with valid data', function () {
    Livewire::test(CreateUser::class)
        ->fill([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ])
        ->call('submit')
        ->assertHasNoErrors(['name', 'email']);
    
    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);
});
```

### 4. Validation Errors
```php
it('validates required fields', function () {
    Livewire::test(CreateUser::class)
        ->fill(['email' => ''])
        ->call('submit')
        ->assertHasErrors(['email' => 'required']);
});
```

### 5. Authorization
```php
it('redirects guests to login', function () {
    $this->get('/dashboard')
        ->assertRedirect('/login');
});

it('shows 403 for unauthorized users', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertForbidden();
});
```

## Test File Organization

```
tests/
├── Feature/
│   ├── Http/
│   │   └── Controllers/
│   │       └── DashboardControllerTest.php
│   ├── Livewire/
│   │   ├── CounterTest.php
│   │   ├── CreateUserTest.php
│   │   └── ModalTest.php
│   └── Views/
│       └── DashboardViewTest.php
```

## Test Naming Convention

```php
// Livewire components
it('renders correctly', ...);
it('increments counter on click', ...);
it('validates email format', ...);
it('saves to database on submit', ...);
it('shows success message after save', ...);

// Views
it('renders dashboard for authenticated users', ...);
it('shows user name in header', ...);
it('displays correct page title', ...);
```

## Enforcement Checklist

Before completing ANY interface work:

- [ ] Feature test file exists
- [ ] Rendering test passes
- [ ] Interaction tests pass
- [ ] Validation tests pass (if form)
- [ ] Authorization tests pass (if protected)
- [ ] Database tests pass (if persistence)
- [ ] Edge cases covered

## Integration with Other Skills

- **testing-best-practices:** General testing patterns
- **hexagonal-architecture:** Test each layer appropriately
- **uiux-design-trends:** Test visual components
- **livewire-development:** Livewire-specific patterns

## How to Apply

1. **Before coding:** Write test stubs for the interface
2. **During coding:** Run tests continuously
3. **After coding:** Verify all tests pass
4. **Before commit:** Run full test suite

---

**REMEMBER:** An interface without tests is incomplete. Test first, code second.
