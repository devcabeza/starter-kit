<?php

declare(strict_types=1);

namespace App\Application\User\DTOs;

final readonly class UpdateProfileDTO
{
    public function __construct(
        public int|string $userId,
        public string $name,
        public string $email,
    ) {}
}
