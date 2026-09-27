# Naming Conventions

## File Naming

| Type | Pattern | Example |
|------|---------|---------|
| Domain Model | `{Entity}.php` | `User.php` |
| Eloquent Model | `{Entity}Model.php` | `UserModel.php` |
| Repository Interface | `{Entity}RepositoryInterface.php` | `UserRepositoryInterface.php` |
| Repository Implementation | `{Entity}EloquentRepository.php` | `UserEloquentRepository.php` |
| Use Case | `{Verb}{Entity}Action.php` | `CreateUserAction.php` |
| Query | `{Verb}{Entity}Query.php` | `GetUserQuery.php` |
| DTO | `{Entity}DTO.php` | `UserDTO.php` |
| Command | `{Verb}{Entity}Command.php` | `CreateUserCommand.php` |
| Controller | `{Entity}Controller.php` | `UserController.php` |
| Exception | `{Entity}{Exception}.php` | `UserNotFoundException.php` |
| Event | `{Verb}{Entity}Event.php` | `UserCreatedEvent.php` |

## Class Naming

- **PascalCase** for all classes
- **Singular** for entities (User, not Users)
- **Interface** suffix for contracts
- **Action** suffix for use cases
- **Query** suffix for read operations
- **Command** suffix for write operations
- **DTO** suffix for data transfer objects

## Namespace Naming

```php
// Domain
namespace App\Domain\User\Models;
namespace App\Domain\User\Exceptions;
namespace App\Domain\User\Events;

// Application
namespace App\Application\User\Commands;
namespace App\Application\User\Queries;
namespace App\Application\User\Services;
namespace App\Application\User\DTOs;

// Ports
namespace App\Ports\In\Http\User;
namespace App\Ports\Out\Persistence;

// Infrastructure
namespace App\Infrastructure\Persistence\Eloquent;
namespace App\Infrastructure\Persistence\Cache;
```

## Variable Naming

- **camelCase** for variables and methods
- **snake_case** for database columns
- **PascalCase** for classes and interfaces
- **UPPER_SNAKE_CASE** for constants

## Method Naming

- **camelCase** for all methods
- **get{Entity}** for queries
- **create{Entity}** for creation
- **update{Entity}** for updates
- **delete{Entity}** for deletions
- **findBy{Criteria}** for searches