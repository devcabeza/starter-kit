<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Auth\Models\MagicLinkToken as DomainToken;
use App\Infrastructure\Persistence\Eloquent\Models\EloquentMagicLinkToken;
use App\Ports\Out\Persistence\MagicLinkTokenRepositoryInterface;
use DateTimeImmutable;

final class EloquentMagicLinkTokenRepository implements MagicLinkTokenRepositoryInterface
{
    public function createToken(
        int|string $userId,
        string $email,
        string $tokenHash,
        DateTimeImmutable $expiresAt,
        ?string $ip = null,
        ?string $userAgent = null,
    ): DomainToken {
        $model = EloquentMagicLinkToken::create([
            'user_id' => $userId,
            'email' => strtolower(trim($email)),
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);

        return $this->toDomain($model);
    }

    public function findValidToken(string $email, string $tokenHash): ?DomainToken
    {
        $model = EloquentMagicLinkToken::where('email', strtolower(trim($email)))
            ->where('token_hash', $tokenHash)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function markAsUsed(int|string $tokenId): void
    {
        EloquentMagicLinkToken::where('id', $tokenId)->update([
            'used_at' => now(),
        ]);
    }

    public function invalidateTokensForEmail(string $email): void
    {
        EloquentMagicLinkToken::where('email', strtolower(trim($email)))
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->update([
                'expires_at' => now(),
            ]);
    }

    private function toDomain(EloquentMagicLinkToken $model): DomainToken
    {
        return new DomainToken(
            id: $model->id,
            userId: $model->user_id,
            email: $model->email,
            tokenHash: $model->token_hash,
            expiresAt: DateTimeImmutable::createFromInterface($model->expires_at),
            usedAt: $model->used_at ? DateTimeImmutable::createFromInterface($model->used_at) : null,
            createdAt: $model->created_at ? DateTimeImmutable::createFromInterface($model->created_at) : null,
        );
    }
}
