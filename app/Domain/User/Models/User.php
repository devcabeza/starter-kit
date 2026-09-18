<?php

declare(strict_types=1);

namespace App\Domain\User\Models;

use DateTimeImmutable;

final readonly class User
{
    public function __construct(
        public int|string $id,
        public string $name,
        public string $email,
        public ?DateTimeImmutable $emailVerifiedAt = null,
        public ?DateTimeImmutable $createdAt = null,
    ) {}

    /**
     * Determine if the user has verified their email address.
     */
    public function isEmailVerified(): bool
    {
        return $this->emailVerifiedAt !== null;
    }
}
