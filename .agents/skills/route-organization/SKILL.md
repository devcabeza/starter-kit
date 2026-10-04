---
name: route-organization
description: "Apply this skill when creating, organizing, or modifying routes in the routes/ folder. This skill defines the route structure, naming conventions, and registration patterns for scalable Laravel applications. Use when: adding new routes, organizing route files, registering route groups, configuring middleware for routes, or refactoring existing route organization."
license: MIT
metadata:
  author: starter-kit
---

# Route Organization

## Core Principle

Routes should be organized by **feature/domain**, not by HTTP method. Group related routes together by business feature, creating clear boundaries between different areas of your application.

## Directory Structure

```
routes/
├── web/                    # Web routes (session-based auth)
│   ├── index.php          # Home/welcome routes
│   ├── auth.php           # Authentication (login, register, password)
│   ├── dashboard.php      # User dashboard
│   ├── settings.php       # User settings
│   ├── admin.php          # Admin panel routes
│   └── {feature}.php      # One file per feature
├── api/                    # API routes (token/Sanctum auth)
│   ├── v1/                # API version 1
│   │   ├── index.php      # API v1 root routes
│   │   ├── users.php      # User endpoints
│   │   └── {resource}.php # One file per resource
│   └── v2/                # API version 2 (future)
├── console.php            # Artisan command routes
└── channel.php            # Broadcast channels (if needed)
```

## File Organization

**One route file per feature/domain.** Never put all routes in one file. Feature files are optional - only create when a feature has routes.

| Feature | File | Description |
|---------|------|-------------|
| Home | `web/index.php` | Welcome page, public pages |
| Auth | `web/auth.php` | Login, register, password reset |
| Dashboard | `web/dashboard.php` | User dashboard |
| Settings | `web/settings.php` | User settings |
| Admin | `web/admin.php` | Admin panel |
| API Users | `api/v1/users.php` | User API endpoints |

## Registration in bootstrap/app.php

Register main entry points in `bootstrap/app.php`:

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

Load additional feature files using `require` in the main route files:

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

## Naming Conventions

### Route Names

Use dot notation with `feature.action` pattern:

| Feature | Route Name | Example |
|---------|------------|---------|
| Home | `home` | `Route::view('/', 'welcome')->name('home')` |
| Dashboard | `dashboard.index` | `->name('dashboard.index')` |
| User CRUD | `users.{action}` | `users.index`, `users.create`, `users.store` |
| Settings | `settings.{section}` | `settings.profile`, `settings.password` |

### URL Slugs

- Use kebab-case for URLs: `/user-profile`, not `/userProfile`
- Use plural nouns for resources: `/users`, not `/user`
- Use IDs for specific resources: `/users/{user}`

### Parameter Naming

- Use singular for route parameters: `{user}`, `{post}`
- Use descriptive names: `{user}`, not `{id}`

```php
// Good
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');

// Bad
Route::get('/getUsers', [UserController::class, 'index'])->name('getUsers');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
```

## Middleware Groups

### Common Middleware

| Group | Purpose | Use Case |
|-------|---------|----------|
| `web` | Session, CSRF, cookies | All web routes |
| `auth` | Authentication check | Protected web routes |
| `guest` | Redirect if authenticated | Login, register pages |
| `verified` | Email verification | Dashboard, settings |
| `admin` | Admin role check | Admin panel |
| `api` | Sanctum, throttle | API routes |

### Route Group Patterns

**Public Routes:**
```php
Route::get('/', fn () => view('welcome'))->name('home');
```

**Authenticated Routes:**
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});
```

**Guest Routes:**
```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
});
```

**Admin Routes:**
```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
});
```

## API Routes

### Versioning

Always version API routes. Use `v1`, `v2`, etc.

```php
// routes/api/v1/index.php
<?php

use Illuminate\Support\Facades\Route;

// Public API routes
Route::get('/health', fn () => response()->json(['status' => 'ok']))->name('api.v1.health');

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    require __DIR__.'/users.php';
    require __DIR__.'/posts.php';
});
```

### API Resource Routes

```php
// routes/api/v1/users.php
<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->name('api.v1.users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});
```

## Hexagonal Architecture Integration

Routes are in the **Ports/In/Http** layer:

```
app/
├── Ports/
│   └── In/
│       └── Http/
│           ├── Controllers/
│           │   ├── Auth/
│           │   ├── Dashboard/
│           │   └── Admin/
│           └── Middleware/
└── Infrastructure/
    └── Http/
        └── Kernel.php
```

Routes define **what** the application does (Ports). Controllers implement **how** it does it (Infrastructure).
