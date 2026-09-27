# Complete Example: User Feature

## 1. Domain Layer

### Domain Model
```php
<?php
// app/Domain/User/Models/User.php

declare(strict_types=1);

namespace App\Domain\User\Models;

class User
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $name,
        public readonly \DateTimeImmutable $createdAt,
    ) {}
    
    public static function create(string $id, string $email, string $name): self
    {
        return new self(
            id: $id,
            email: $email,
            name: $name,
            createdAt: new \DateTimeImmutable(),
        );
    }
    
    public function changeName(string $newName): self
    {
        return new self(
            id: $this->id,
            email: $this->email,
            name: $newName,
            createdAt: $this->createdAt,
        );
    }
}
```

### Domain Exception
```php
<?php
// app/Domain/User/Exceptions/UserNotFoundException.php

declare(strict_types=1);

namespace App\Domain\User\Exceptions;

class UserNotFoundException extends \RuntimeException
{
    public static function withId(string $id): self
    {
        return new self("User with ID {$id} not found");
    }
}
```

## 2. Ports Layer

### Repository Interface
```php
<?php
// app/Ports/Out/Persistence/UserRepositoryInterface.php

declare(strict_types=1);

namespace App\Ports\Out\Persistence;

use App\Domain\User\Models\User;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;
    public function findByEmail(string $email): ?User;
    public function save(User $user): void;
    public function delete(string $id): void;
}
```

### Controller (Driving Port)
```php
<?php
// app/Ports/In/Http/User/UserController.php

declare(strict_types=1);

namespace App\Ports\In\Http\User;

use App\Application\User\Commands\CreateUserCommand;
use App\Application\User\Queries\GetUserQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    public function __construct(
        private CreateUserCommand $createUser,
        private GetUserQuery $getUser,
    ) {}
    
    public function store(Request $request): JsonResponse
    {
        $user = $this->createUser->execute(
            new \App\Application\User\DTOs\CreateUserDTO(
                email: $request->email,
                name: $request->name,
            )
        );
        
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ], 201);
    }
    
    public function show(string $id): JsonResponse
    {
        $user = $this->getUser->execute($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ]);
    }
}
```

## 3. Application Layer

### DTO
```php
<?php
// app/Application/User/DTOs/CreateUserDTO.php

declare(strict_types=1);

namespace App\Application\User\DTOs;

class CreateUserDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $name,
    ) {}
}
```

### Command
```php
<?php
// app/Application/User/Commands/CreateUserCommand.php

declare(strict_types=1);

namespace App\Application\User\Commands;

use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\Models\User;
use App\Ports\Out\Persistence\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;

class CreateUserCommand
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}
    
    public function execute(CreateUserDTO $dto): User
    {
        $user = User::create(
            id: Uuid::uuid4()->toString(),
            email: $dto->email,
            name: $dto->name,
        );
        
        $this->userRepository->save($user);
        
        return $user;
    }
}
```

### Query
```php
<?php
// app/Application/User/Queries/GetUserQuery.php

declare(strict_types=1);

namespace App\Application\User\Queries;

use App\Domain\User\Models\User;
use App\Ports\Out\Persistence\UserRepositoryInterface;

class GetUserQuery
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}
    
    public function execute(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}
```

## 4. Infrastructure Layer

### Eloquent Model
```php
<?php
// app/Infrastructure/Persistence/Eloquent/Models/UserModel.php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $fillable = ['id', 'email', 'name'];
    public $timestamps = false;
}
```

### Repository Implementation
```php
<?php
// app/Infrastructure/Persistence/Eloquent/EloquentUserRepository.php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\User\Models\User;
use App\Infrastructure\Persistence\Eloquent\Models\UserModel;
use App\Ports\Out\Persistence\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(string $id): ?User
    {
        $model = UserModel::find($id);
        
        return $model ? $this->toDomain($model) : null;
    }
    
    public function findByEmail(string $email): ?User
    {
        $model = UserModel::where('email', $email)->first();
        
        return $model ? $this->toDomain($model) : null;
    }
    
    public function save(User $user): void
    {
        UserModel::updateOrCreate(
            ['id' => $user->id],
            [
                'email' => $user->email,
                'name' => $user->name,
            ]
        );
    }
    
    public function delete(string $id): void
    {
        UserModel::where('id', $id)->delete();
    }
    
    private function toDomain(UserModel $model): User
    {
        return new User(
            id: $model->id,
            email: $model->email,
            name: $model->name,
            createdAt: \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at ?? now()->toDateTimeString()),
        );
    }
}
```

### Service Provider
```php
<?php
// app/Providers/HexagonalServiceProvider.php

declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use App\Ports\Out\Persistence\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class HexagonalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }
}
```

## 5. Routes

```php
// routes/api.php
use App\Ports\In\Http\User\UserController;

Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
```

---

## Summary

This is a complete, production-ready example of Hexagonal Architecture in Laravel. Every piece has its place, and dependencies flow inward toward the Domain.