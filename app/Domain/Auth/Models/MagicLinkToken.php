<?php

declare(strict_types=1);

namespace App\Domain\Auth\Models;

use DateTimeImmutable;

final readonly class MagicLinkToken
{
    public function __construct(
        public int|string $id,
        public int|string $userId,
        public string $email,
        public string $tokenHash,
        public DateTimeImmutable $expiresAt,
        public ?DateTimeImmutable $usedAt = null,
        public ?DateTimeImmutable $createdAt = null,
    ) {}

    /**
     * Determine if the token has expired.
     */
    public function isExpired(?DateTimeImmutable $now = null): bool
    {
        $comparison = $now ?? new DateTimeImmutable;

        return $this->expiresAt <= $comparison;
    }

    /**
     * Determine if the token has already been used.
     */
    public function isUsed(): bool
    {
        return $this->usedAt !== null;
    }

    /**
     * Determine if the token is valid for authentication.
     */
    public function isValid(?DateTimeImmutable $now = null): bool
    {
        return ! $this->isUsed() && ! $this->isExpired($now);
    }
}
