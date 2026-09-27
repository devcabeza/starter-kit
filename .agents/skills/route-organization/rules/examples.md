# Route Organization Examples

## Example 1: Simple Web Application

### routes/web/index.php
```php
<?php

use Illuminate\Support\Facades\Route;

// Public routes
Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');

// Feature routes
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/settings.php';
```

### routes/web/auth.php
```php
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/forgot-password', [PasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'store'])->name('password.email');
});
```

### routes/web/dashboard.php
```php
<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});
```

## Example 2: API with Versioning

### routes/api/v1/index.php
```php
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

### routes/api/v1/users.php
```php
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

## Example 3: Admin Panel

### routes/web/admin.php
```php
<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    
    Route::resource('users', UserController::class)->except(['create', 'store']);
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
