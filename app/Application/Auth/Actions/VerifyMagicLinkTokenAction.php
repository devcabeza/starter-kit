<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions;

use App\Application\Auth\DTOs\VerifyMagicLinkDTO;
use App\Domain\Auth\Exceptions\InvalidMagicLinkTokenException;
use App\Domain\Auth\Exceptions\MagicLinkTokenAlreadyUsedException;
use App\Domain\Auth\Exceptions\MagicLinkTokenExpiredException;
use App\Domain\User\Models\User;
use App\Ports\Out\Persistence\MagicLinkTokenRepositoryInterface;
use App\Ports\Out\Persistence\UserRepositoryInterface;

final class VerifyMagicLinkTokenAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly MagicLinkTokenRepositoryInterface $tokenRepository,
    ) {}

    /**
     * @throws InvalidMagicLinkTokenException
     * @throws MagicLinkTokenExpiredException
     * @throws MagicLinkTokenAlreadyUsedException
     */
    public function execute(VerifyMagicLinkDTO $dto): User
    {
        $normalizedEmail = strtolower(trim($dto->email));
        $normalizedToken = trim($dto->token);

        if ($normalizedToken === '' || $normalizedEmail === '') {
            throw new InvalidMagicLinkTokenException;
        }

        $tokenHash = hash('sha256', $normalizedToken);

        $token = $this->tokenRepository->findValidToken($normalizedEmail, $tokenHash);

        if ($token === null) {
            throw new InvalidMagicLinkTokenException;
        }

        if ($token->isExpired()) {
            throw new MagicLinkTokenExpiredException;
        }

        if ($token->isUsed()) {
            throw new MagicLinkTokenAlreadyUsedException;
        }

        // Mark token as used
        $this->tokenRepository->markAsUsed($token->id);

        // Mark email as verified
        $this->userRepository->markEmailAsVerified($token->userId);

        $user = $this->userRepository->findById($token->userId);

        if ($user === null) {
            throw new InvalidMagicLinkTokenException('No se encontró el usuario asociado a este código.');
        }

        return $user;
    }
}
