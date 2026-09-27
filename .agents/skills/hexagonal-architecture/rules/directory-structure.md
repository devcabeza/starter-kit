# Directory Structure Rules

## Mandatory Layout

Every feature MUST be organized in this structure:

```
app/
├── Domain/
│   └── {Feature}/
│       ├── Models/
│       │   └── {Entity}.php
│       ├── Exceptions/
│       │   └── {Entity}NotFoundException.php
│       └── Events/
│           └── {Entity}CreatedEvent.php
│
├── Application/
│   └── {Feature}/
│       ├── Commands/
│       │   └── Create{Entity}Command.php
│       ├── Queries/
│       │   └── Get{Entity}Query.php
│       ├── Services/
│       │   └── {Entity}Service.php
│       └── DTOs/
│           └── {Entity}DTO.php
│
├── Ports/
│   ├── In/
│   │   └── Http/
│   │       └── {Feature}/
│   │           └── {Entity}Controller.php
│   └── Out/
│       └── Persistence/
│           └── {Entity}RepositoryInterface.php
│
└── Infrastructure/
    └── Persistence/
        └── Eloquent/
            └── {Entity}EloquentRepository.php
```

## File Creation Order

1. Domain models (pure PHP)
2. Ports interfaces (contracts)
3. Application services (use cases)
4. Infrastructure adapters (implementations)
5. Controllers (entry points)

## Common Mistakes

**❌ WRONG:** Putting everything in `app/Http/Controllers`
**✅ RIGHT:** Organizing by layer and feature

**❌ WRONG:** Domain model extends Eloquent Model
**✅ RIGHT:** Domain model is pure PHP, Eloquent model is in Infrastructure