<?php

declare(strict_types=1);

namespace App\Ports\Out\Persistence;

use App\Domain\User\Models\User;

interface UserRepositoryInterface
{
    /**
     * Find a user by email address.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find a user by primary ID.
     */
    public function findById(int|string $id): ?User;

    /**
     * Create a new user with email and name.
     */
    public function create(string $email, string $name): User;

    /**
     * Mark the user's email address as verified.
     */
    public function markEmailAsVerified(int|string $id): void;

    /**
     * Determine if an email address is already registered by another user.
     */
    public function emailExistsExceptUser(string $email, int|string $exceptUserId): bool;

    /**
     * Update a user's name and email address.
     */
    public function updateProfile(int|string $id, string $name, string $email): User;

    /**
     * Delete a user account by primary ID.
     */
    public function delete(int|string $id): void;
}
