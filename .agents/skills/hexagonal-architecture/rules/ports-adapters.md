# Ports & Adapters Pattern

## Ports (Interfaces)

Ports define WHAT the application needs, not HOW it's done.

### Location
`app/Ports/In/` - Driving ports (what the app does)
`app/Ports/Out/` - Driven ports (what the app uses)

### Naming
```php
// Interface
interface UserRepositoryInterface
{
    public function findById(string $id): ?User;
    public function save(User $user): void;
}

// Command interface
interface CreateUserInterface
{
    public function execute(CreateUserDTO $dto): User;
}

// Query interface
interface GetUserInterface
{
    public function execute(string $id): ?User;
}
```

## Adapters (Implementations)

Adapters implement the ports using specific technologies.

### Location
`app/Infrastructure/Persistence/Eloquent/` - Database adapters
`app/Infrastructure/Persistence/Cache/` - Cache adapters
`app/Infrastructure/External/` - External service adapters

### Naming
```php
// Repository adapter
class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(string $id): ?User
    {
        $model = \App\Infrastructure\Persistence\Eloquent\Models\UserModel::find($id);
        return $model ? $this->toDomain($model) : null;
    }
    
    private function toDomain(UserModel $model): User
    {
        return new User(
            id: $model->id,
            email: $model->email,
            name: $model->name,
        );
    }
}
```

## Binding

```php
// In service provider
$this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

// For multiple implementations
$this->app->bind(UserRepositoryInterface::class, function ($app) {
    if (config('cache.default') === 'redis') {
        return $app->make(CacheUserRepository::class);
    }
    return $app->make(EloquentUserRepository::class);
});
```

## Benefits

1. **Testability:** Mock ports for unit testing
2. **Flexibility:** Swap implementations without changing business logic
3. **Clarity:** Clear separation of concerns