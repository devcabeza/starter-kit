<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\User\Models\User as DomainUser;
use App\Models\User as EloquentUser;
use App\Ports\Out\Persistence\UserRepositoryInterface;
use DateTimeImmutable;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?DomainUser
    {
        $user = EloquentUser::where('email', strtolower(trim($email)))->first();

        return $user ? $this->toDomain($user) : null;
    }

    public function findById(int|string $id): ?DomainUser
    {
        $user = EloquentUser::find($id);

        return $user ? $this->toDomain($user) : null;
    }

    public function create(string $email, string $name): DomainUser
    {
        $user = EloquentUser::create([
            'email' => strtolower(trim($email)),
            'name' => trim($name),
            'password' => null,
        ]);

        return $this->toDomain($user);
    }

    public function markEmailAsVerified(int|string $id): void
    {
        EloquentUser::where('id', $id)
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    private function toDomain(EloquentUser $model): DomainUser
    {
        return new DomainUser(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            emailVerifiedAt: $model->email_verified_at
                ? DateTimeImmutable::createFromInterface($model->email_verified_at)
                : null,
            createdAt: $model->created_at
                ? DateTimeImmutable::createFromInterface($model->created_at)
                : null,
        );
    }
}
