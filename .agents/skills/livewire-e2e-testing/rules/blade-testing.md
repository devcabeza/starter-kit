# Blade View Testing

## Basic View Test

```php
<?php
// tests/Feature/Http/Controllers/DashboardControllerTest.php

declare(strict_types=1);

use App\Models\User;

it('requires authentication', function () {
    $this->get('/dashboard')
        ->assertRedirect('/login');
});

it('renders for authenticated users', function () {
    $this->actingAs(User::factory()->create())
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Dashboard');
});

it('displays user name', function () {
    $user = User::factory()->create(['name' => 'John']);
    
    $this->actingAs($user)
        ->get('/dashboard')
        ->assertSee('John');
});
```

## Component View Test

```php
<?php
// tests/Feature/Views/Components/AlertTest.php

declare(strict_types=1);

it('renders alert component', function () {
    $this->blade('<x-alert type="success" message="Saved!" />')
        ->assertSee('Saved!')
        ->assertSee('success');
});

it('renders different alert types', function (string $type) {
    $this->blade("<x-alert type=\"{$type}\" message=\"Test\" />")
        ->assertSee('Test');
})->with(['info', 'success', 'warning', 'error']);
```

## Layout Test

```php
<?php
// tests/Feature/Views/Layouts/AppLayoutTest.php

declare(strict_types=1);

use App\Models\User;

it('includes navigation', function () {
    $this->actingAs(User::factory()->create())
        ->get('/dashboard')
        ->assertSee('Navigation');
});

it('includes footer', function () {
    $this->actingAs(User::factory()->create())
        ->get('/dashboard')
        ->assertSee('Footer');
});

it('sets page title', function () {
    $this->actingAs(User::factory()->create())
        ->get('/dashboard')
        ->assertSee('Dashboard - MyApp');
});
```

## Form View Test

```php
<?php
// tests/Feature/Views/Forms/CreatePostFormTest.php

declare(strict_types=1);

it('renders form fields', function () {
    $this->blade('<x-forms.create-post />')
        ->assertSee('Title')
        ->assertSee('Content')
        ->assertSee('Submit');
});

it('has correct form action', function () {
    $this->blade('<x-forms.create-post />')
        ->assertSee('action="/posts"', false);
});

it('includes CSRF token', function () {
    $this->blade('<x-forms.create-post />')
        ->assertSee('_token', false);
});
```

## Conditional Rendering Test

```php
<?php
// tests/Feature/Views/ConditionalRenderingTest.php

declare(strict_types=1);

use App\Models\User;

it('shows admin panel for admins', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get('/dashboard')
        ->assertSee('Admin Panel');
});

it('hides admin panel for regular users', function () {
    $this->actingAs(User::factory()->create())
        ->get('/dashboard')
        ->assertDontSee('Admin Panel');
});
```

## Available Blade Test Methods

```php
// Render blade string
$this->blade('<x-alert />');

// Assert HTML content
->assertSee('content')
->assertDontSee('hidden')
->assertSeeHtml('<div')
->assertDontSeeHtml('<script>');

// Assert attributes
->assertSee('class="btn"', false);

// Assert links
->assertSee('href="/login"', false);
```

## Testing Blade Components

```php
<?php
// tests/Feature/View/Components/CardTest.php

declare(strict_types=1);

use App\View\Components\Card;

it('renders with default variant', function () {
    $component = new Card();
    
    $this->blade('<x-card>{{ $slot }}</x-card>')
        ->assertSee('card');
});

it('renders with primary variant', function () {
    $this->blade('<x-card variant="primary">{{ $slot }}</x-card>')
        ->assertSee('primary');
});
```
