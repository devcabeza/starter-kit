# Route Registration

## bootstrap/app.php Pattern

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web/index.php',
        api: __DIR__.'/../routes/api/v1/index.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->create();
```

## Loading Additional Route Files

### Option 1: In Route Files (Recommended)

Load additional files within the main route file:

```php
// routes/web/index.php
<?php

use Illuminate\Support\Facades\Route;

// Load feature route files
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
```

### Option 2: In Service Provider

For complex loading logic:

```php
// app/Providers/RouteServiceProvider.php
public function boot(): void
{
    $this->routes(function () {
        Route::middleware('web')
            ->group(base_path('routes/web/index.php'));
    });
}
```

## Loading Order

1. Main route file (index.php) loads first
2. Feature files load in order they're required
3. Later routes can override earlier ones (last wins)

## Best Practices

1. **Use require in index.php** - Simple, explicit, no magic
2. **Keep bootstrap/app.php minimal** - Only main entry points
3. **Document loading order** - Add comments in index.php
