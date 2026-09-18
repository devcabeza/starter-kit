<?php

declare(strict_types=1);

namespace App\Ports\Out\Persistence;

use App\Domain\Auth\Models\MagicLinkToken;
use DateTimeImmutable;

interface MagicLinkTokenRepositoryInterface
{
    /**
     * Store a new magic link token in persistence.
     */
    public function createToken(
        int|string $userId,
        string $email,
        string $tokenHash,
        DateTimeImmutable $expiresAt,
        ?string $ip = null,
        ?string $userAgent = null,
    ): MagicLinkToken;

    /**
     * Find the latest valid token matching email and token hash.
     */
    public function findValidToken(string $email, string $tokenHash): ?MagicLinkToken;

    /**
     * Mark a token as used.
     */
    public function markAsUsed(int|string $tokenId): void;

    /**
     * Invalidate (mark expired or delete) all previous pending tokens for an email.
     */
    public function invalidateTokensForEmail(string $email): void;
}
