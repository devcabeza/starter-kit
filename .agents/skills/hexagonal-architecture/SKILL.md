---
name: hexagonal-architecture
description: "Apply this skill whenever planning, creating, editing, or reviewing features in this project. This skill enforces strict Hexagonal Architecture (Ports & Adapters) and Clean Code principles for Laravel. Use when: creating new features, refactoring existing code, reviewing PRs, planning architecture, creating controllers, services, repositories, models, or any PHP class. MANDATORY for all new code."
license: MIT
metadata:
  author: starter-kit
---

# Hexagonal Architecture (Ports & Adapters)

**ESTRICT RULES** - This skill defines the architectural foundation for this project. All code MUST follow these rules without exception.

## Core Principle

**Dependencies point INWARD.** The domain is the center. Infrastructure is the outside. Nothing in the inside knows about the outside.

```
┌─────────────────────────────────────────────────┐
│                  INFRASTRUCTURE                  │
│  ┌───────────────────────────────────────────┐  │
│  │              APPLICATION                   │  │
│  │  ┌─────────────────────────────────────┐  │  │
│  │  │              DOMAIN                  │  │  │
│  │  │         (Business Logic)            │  │  │
│  │  └─────────────────────────────────────┘  │  │
│  │                                           │  │
│  │         (Use Cases / Commands)            │  │
│  └───────────────────────────────────────────┘  │
│                                                 │
│      (Controllers, Repositories, Services)      │
└─────────────────────────────────────────────────┘
```

## Mandatory Directory Structure

```
app/
├── Domain/                    # BUSINESS LOGIC (center)
│   ├── {Entity}/             # One directory per domain entity
│   │   ├── Models/           # Domain models (NOT Eloquent)
│   │   ├── Exceptions/       # Domain-specific exceptions
│   │   └── Events/           # Domain events
│   └── Shared/               # Shared value objects, traits
│
├── Application/              # USE CASES (orchestration)
│   ├── Commands/             # Command bus handlers
│   ├── Queries/              # Query bus handlers
│   ├── Services/             # Application services
│   └── DTOs/                 # Data Transfer Objects
│
├── Ports/                    # INTERFACES (contracts)
│   ├── In/                   # Driving ports (what the app does)
│   │   ├── Http/            # HTTP controllers
│   │   ├── Console/         # Artisan commands
│   │   └── Events/          # Event listeners
│   └── Out/                  # Driven ports (what the app uses)
│       ├── Persistence/     # Repository interfaces
│       ├── External/        # External service interfaces
│       └── Messaging/       # Queue/Mail interfaces
│
└── Infrastructure/           # ADAPTERS (implementations)
    ├── Persistence/          # Repository implementations
    │   ├── Eloquent/        # Eloquent repositories
    │   └── Cache/           # Cache repositories
    ├── External/            # External service adapters
    ├── Messaging/           # Queue/Mail adapters
    └── persistence/         # Laravel config, service providers
```

## The Three Layers

### 1. Domain Layer (CENTER)
- **Location:** `app/Domain/`
- **Contains:** Business logic, entities, value objects, domain events
- **Dependencies:** NONE (pure PHP, no framework dependencies)
- **Rules:**
  - NO Laravel facades
  - NO Eloquent models
  - NO framework classes
  - ONLY pure PHP with strict types

### 2. Application Layer (MIDDLE)
- **Location:** `app/Application/`
- **Contains:** Use cases, commands, queries, application services
- **Dependencies:** Domain layer ONLY
- **Rules:**
  - Orchestrates domain logic
  - NO infrastructure code
  - Uses interfaces (ports) for external dependencies

### 3. Infrastructure Layer (OUTSIDE)
- **Location:** `app/Infrastructure/` + `app/Ports/`
- **Contains:** Framework code, drivers, adapters
- **Dependencies:** Application and Domain layers
- **Rules:**
  - Implements ports (interfaces)
  - Contains all Laravel-specific code
  - Is the only layer that knows about the database

## Dependency Rule (STRICT)

```
Domain → NOTHING (pure PHP)
Application → Domain ONLY
Infrastructure → Application + Domain
```

**VIOLATION EXAMPLES (NEVER DO):**

```php
// ❌ VIOLATION: Domain depends on Eloquent
namespace App\Domain\User\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model { }

// ✅ CORRECT: Domain model is pure PHP
namespace App\Domain\User\Models;

class User
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $name,
    ) {}
}
```

```php
// ❌ VIOLATION: Application uses Infrastructure directly
namespace App\Application\Services;

use App\Infrastructure\Persistence\EloquentUserRepository;

class UserService
{
    public function __construct(
        private EloquentUserRepository $repository // WRONG!
    ) {}
}

// ✅ CORRECT: Application uses Port (interface)
namespace App\Application\Services;

use App\Ports\Out\Persistence\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $repository // RIGHT!
    ) {}
}
```

## Ports & Adapters Pattern

### Ports (Interfaces)
- **Location:** `app/Ports/`
- **Purpose:** Define contracts for what the application needs
- **Naming:** `{Concept}Interface.php`

### Adapters (Implementations)
- **Location:** `app/Infrastructure/`
- **Purpose:** Implement the port interfaces
- **Naming:** `{Concept}EloquentRepository.php`, `{Concept}CacheRepository.php`

### Binding (Service Provider)
```php
// In AppServiceProvider or dedicated provider
$this->app->bind(
    UserRepositoryInterface::class,
    EloquentUserRepository::class
);
```

## Naming Conventions (STRICT)

| Element | Pattern | Example |
|---------|---------|---------|
| Domain Entity | Singular, PascalCase | `User`, `Order`, `Product` |
| Domain Model | Entity name | `User`, `Order` |
| Use Case | Verb + Noun + Action | `CreateUserAction`, `GetOrderQuery` |
| Repository Interface | Entity + Interface | `UserRepositoryInterface` |
| Repository Implementation | Entity + Adapter | `EloquentUserRepository` |
| DTO | Entity + DTO | `UserDTO`, `CreateUserDTO` |
| Command | Verb + Noun + Command | `CreateUserCommand` |
| Query | Verb + Noun + Query | `GetUserQuery` |
| Exception | Entity + Exception | `UserNotFoundException` |
| Event | Verb + Noun + Event | `UserCreatedEvent` |

## File Organization Rules

1. **One class per file** - ALWAYS
2. **File name matches class name** - ALWAYS
3. **Namespace matches directory** - ALWAYS
4. **No nested dependencies** - Domain cannot import Application, Application cannot import Infrastructure

## Testing Rules

1. **Domain tests:** Unit tests, no Laravel, no database
2. **Application tests:** Unit tests with mocked ports
3. **Infrastructure tests:** Integration tests with real adapters
4. **Port tests:** Contract tests

## Enforcement Checklist

Before ANY code change, verify:

- [ ] Domain layer has ZERO Laravel imports
- [ ] Application layer only imports Domain + Ports
- [ ] Infrastructure implements Ports, not Domain
- [ ] Dependencies point INWARD (toward Domain)
- [ ] One class per file
- [ ] Naming follows conventions
- [ ] No circular dependencies

## How to Apply

1. **New Features:** Start from Domain, then Application, then Infrastructure
2. **Refactoring:** Extract Domain logic from existing code
3. **Code Review:** Check dependency direction first
4. **Planning:** Map features to layers before coding

## Violation Response

**ZERO TOLERANCE** for architecture violations. If you find a violation:
1. STOP working on the feature
2. Report the violation
3. Fix the architecture first
4. Then continue with the feature

---

**REMEMBER:** Hexagonal Architecture is not optional. It's the foundation of this project. Every line of code must respect these rules.