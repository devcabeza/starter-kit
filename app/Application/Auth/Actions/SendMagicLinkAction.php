<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions;

use App\Application\Auth\DTOs\SendMagicLinkDTO;
use App\Ports\Out\Messaging\MagicLinkNotifierInterface;
use App\Ports\Out\Persistence\MagicLinkTokenRepositoryInterface;
use App\Ports\Out\Persistence\UserRepositoryInterface;
use DateTimeImmutable;

final class SendMagicLinkAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly MagicLinkTokenRepositoryInterface $tokenRepository,
        private readonly MagicLinkNotifierInterface $notifier,
    ) {}

    public function execute(SendMagicLinkDTO $dto): void
    {
        $normalizedEmail = strtolower(trim($dto->email));

        $user = $this->userRepository->findByEmail($normalizedEmail);

        if ($user === null) {
            $defaultName = $this->deriveNameFromEmail($normalizedEmail);
            $user = $this->userRepository->create($normalizedEmail, $defaultName);
        }

        // Invalidate any existing active tokens for this email
        $this->tokenRepository->invalidateTokensForEmail($normalizedEmail);

        // Generate a 6-digit numeric token
        $rawToken = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $tokenHash = hash('sha256', $rawToken);
        $expiresAt = (new DateTimeImmutable)->modify('+15 minutes');

        // Persist token
        $this->tokenRepository->createToken(
            userId: $user->id,
            email: $normalizedEmail,
            tokenHash: $tokenHash,
            expiresAt: $expiresAt,
            ip: $dto->ipAddress,
            userAgent: $dto->userAgent,
        );

        // Notify user via queued email
        $this->notifier->send(
            email: $normalizedEmail,
            rawToken: $rawToken,
            expiresAt: $expiresAt,
        );
    }

    private function deriveNameFromEmail(string $email): string
    {
        $parts = explode('@', $email);
        $localPart = $parts[0];
        $cleaned = preg_replace('/[^a-zA-Z0-9]/', ' ', $localPart) ?: 'Usuario';

        return ucwords(trim($cleaned));
    }
}
