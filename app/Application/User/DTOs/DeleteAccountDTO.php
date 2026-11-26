<?php

declare(strict_types=1);

namespace App\Application\User\DTOs;

final readonly class DeleteAccountDTO
{
    public function __construct(
        public int|string $userId,
    ) {}
}
