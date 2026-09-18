<?php

declare(strict_types=1);

namespace App\Application\Auth\DTOs;

final readonly class SendMagicLinkDTO
{
    public function __construct(
        public string $email,
        public ?string $ipAddress = null,
        public ?string $userAgent = null,
    ) {}
}
