# Route Naming Conventions

## Route Names

Use dot notation with `feature.action` pattern:

| Feature | Route Name | Example |
|---------|------------|---------|
| Home | `home` | `Route::view('/', 'welcome')->name('home')` |
| Dashboard | `dashboard.index` | `->name('dashboard.index')` |
| User CRUD | `users.{action}` | `users.index`, `users.create`, `users.store` |
| Settings | `settings.{section}` | `settings.profile`, `settings.password` |

## URL Slugs

- Use kebab-case for URLs: `/user-profile`, not `/userProfile`
- Use plural nouns for resources: `/users`, not `/user`
- Use IDs for specific resources: `/users/{user}`

## Parameter Naming

- Use singular for route parameters: `{user}`, `{post}`
- Use descriptive names: `{user}`, not `{id}`

## Examples

```php
// Good
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');

// Bad
Route::get('/getUsers', [UserController::class, 'index'])->name('getUsers');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
```

## Naming Patterns

| Resource | Index | Create | Store | Show | Edit | Update | Destroy |
|----------|-------|--------|-------|------|------|--------|---------|
| Users | `users.index` | `users.create` | `users.store` | `users.show` | `users.edit` | `users.update` | `users.destroy` |
| Posts | `posts.index` | `posts.create` | `posts.store` | `posts.show` | `posts.edit` | `posts.update` | `posts.destroy` |

## Nested Resources

```php
// Posts with comments
Route::resource('posts.comments', CommentController::class);
// Route names: posts.comments.index, posts.comments.show, etc.
```

## API Route Names

Prefix API route names with version:

```php
Route::prefix('users')->name('api.v1.users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
});
```
