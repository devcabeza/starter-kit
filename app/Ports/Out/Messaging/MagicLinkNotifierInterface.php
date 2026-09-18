<?php

declare(strict_types=1);

namespace App\Ports\Out\Messaging;

use DateTimeImmutable;

interface MagicLinkNotifierInterface
{
    /**
     * Send a magic link notification (email) containing the token code and direct link.
     */
    public function send(string $email, string $rawToken, DateTimeImmutable $expiresAt): void;
}
