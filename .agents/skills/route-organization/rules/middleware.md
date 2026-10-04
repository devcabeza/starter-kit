# Middleware Patterns

## Common Middleware Groups

| Group | Purpose | Use Case |
|-------|---------|----------|
| `web` | Session, CSRF, cookies | All web routes |
| `auth` | Authentication check | Protected web routes |
| `guest` | Redirect if authenticated | Login, register pages |
| `verified` | Email verification | Dashboard, settings |
| `admin` | Admin role check | Admin panel |
| `api` | Sanctum, throttle | API routes |

## Route Group Patterns

### Public Routes
```php
Route::get('/', fn () => view('welcome'))->name('home');
```

### Authenticated Routes
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});
```

### Guest Routes
```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
});
```

### Admin Routes
```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
});
```

### API Routes
```php
Route::middleware('auth:sanctum')->prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/users', [UserApiController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserApiController::class, 'show'])->name('users.show');
});
```

## Middleware Naming

- Use descriptive middleware names: `auth`, `admin`, `verified`
- Create custom middleware for business logic: `CheckSubscription`, `EnsureTeamExists`
- Register custom middleware in `bootstrap/app.php` or `app/Http/Kernel.php`
