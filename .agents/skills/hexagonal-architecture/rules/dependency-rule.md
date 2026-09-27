# The Dependency Rule

## The Golden Rule

**Dependencies must point INWARD toward the Domain.**

```
Domain → NOTHING (pure PHP)
Application → Domain ONLY
Infrastructure → Application + Domain
Ports → NOTHING (interfaces only)
```

## What This Means

### Domain Layer
- CANNOT import from Application
- CANNOT import from Infrastructure
- CANNOT use Laravel facades
- CANNOT use Eloquent
- CAN use only pure PHP

### Application Layer
- CANNOT import from Infrastructure
- CAN import from Domain
- CAN import from Ports (interfaces)
- CANNOT use Laravel facades directly (use ports)

### Infrastructure Layer
- CAN import from Application
- CAN import from Domain
- CAN import from Ports
- MUST implement Port interfaces

## Dependency Injection

Always inject interfaces, never concrete classes:

```php
// ❌ WRONG
public function __construct(private EloquentUserRepository $repo) {}

// ✅ CORRECT
public function __construct(private UserRepositoryInterface $repo) {}
```

## Service Provider Binding

Bind interfaces to implementations in service providers:

```php
$this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
```

## Checking Dependencies

Before writing code, ask:
1. What layer am I in?
2. What am I importing?
3. Does this import point INWARD?

If the answer to #3 is NO, you're violating the architecture.