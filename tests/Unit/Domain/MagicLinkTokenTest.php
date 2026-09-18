<?php

declare(strict_types=1);

use App\Domain\Auth\Models\MagicLinkToken;

test('token is valid when not used and not expired', function () {
    $token = new MagicLinkToken(
        id: 1,
        userId: 1,
        email: 'user@example.com',
        tokenHash: hash('sha256', '123456'),
        expiresAt: (new DateTimeImmutable)->modify('+15 minutes'),
    );

    expect($token->isUsed())->toBeFalse();
    expect($token->isExpired())->toBeFalse();
    expect($token->isValid())->toBeTrue();
});

test('token is invalid when expired', function () {
    $token = new MagicLinkToken(
        id: 1,
        userId: 1,
        email: 'user@example.com',
        tokenHash: hash('sha256', '123456'),
        expiresAt: (new DateTimeImmutable)->modify('-1 minute'),
    );

    expect($token->isExpired())->toBeTrue();
    expect($token->isValid())->toBeFalse();
});

test('token is invalid when already used', function () {
    $token = new MagicLinkToken(
        id: 1,
        userId: 1,
        email: 'user@example.com',
        tokenHash: hash('sha256', '123456'),
        expiresAt: (new DateTimeImmutable)->modify('+15 minutes'),
        usedAt: new DateTimeImmutable,
    );

    expect($token->isUsed())->toBeTrue();
    expect($token->isValid())->toBeFalse();
});
