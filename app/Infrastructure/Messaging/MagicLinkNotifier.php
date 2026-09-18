<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging;

use App\Infrastructure\Mail\MagicLinkMail;
use App\Ports\Out\Messaging\MagicLinkNotifierInterface;
use DateTimeImmutable;
use Illuminate\Support\Facades\Mail;

final class MagicLinkNotifier implements MagicLinkNotifierInterface
{
    public function send(string $email, string $rawToken, DateTimeImmutable $expiresAt): void
    {
        $verificationUrl = route('auth.verify', [
            'email' => $email,
            'token' => $rawToken,
        ]);

        $now = new DateTimeImmutable;
        $diffSeconds = max(0, $expiresAt->getTimestamp() - $now->getTimestamp());
        $expiresInMinutes = (int) ceil($diffSeconds / 60);

        Mail::to($email)->send(new MagicLinkMail(
            emailAddress: $email,
            tokenCode: $rawToken,
            verificationUrl: $verificationUrl,
            expiresInMinutes: $expiresInMinutes,
        ));
    }
}
