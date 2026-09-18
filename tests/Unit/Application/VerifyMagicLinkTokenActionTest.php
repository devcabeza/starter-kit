<?php

declare(strict_types=1);

use App\Application\Auth\Actions\VerifyMagicLinkTokenAction;
use App\Application\Auth\DTOs\VerifyMagicLinkDTO;
use App\Domain\Auth\Exceptions\InvalidMagicLinkTokenException;
use App\Domain\Auth\Exceptions\MagicLinkTokenAlreadyUsedException;
use App\Domain\Auth\Exceptions\MagicLinkTokenExpiredException;
use App\Domain\Auth\Models\MagicLinkToken;
use App\Domain\User\Models\User;
use App\Ports\Out\Persistence\MagicLinkTokenRepositoryInterface;
use App\Ports\Out\Persistence\UserRepositoryInterface;

test('throws InvalidMagicLinkTokenException when token is empty or not found', function () {
    $userRepo = Mockery::mock(UserRepositoryInterface::class);
    $tokenRepo = Mockery::mock(MagicLinkTokenRepositoryInterface::class);

    $action = new VerifyMagicLinkTokenAction($userRepo, $tokenRepo);

    $action->execute(new VerifyMagicLinkDTO('test@example.com', ''));
})->throws(InvalidMagicLinkTokenException::class);

test('throws MagicLinkTokenExpiredException when token has expired', function () {
    $userRepo = Mockery::mock(UserRepositoryInterface::class);
    $tokenRepo = Mockery::mock(MagicLinkTokenRepositoryInterface::class);

    $expiredToken = new MagicLinkToken(
        id: 1,
        userId: 10,
        email: 'test@example.com',
        tokenHash: hash('sha256', '123456'),
        expiresAt: (new DateTimeImmutable)->modify('-5 minutes'),
    );

    $tokenRepo->shouldReceive('findValidToken')
        ->once()
        ->andReturn($expiredToken);

    $action = new VerifyMagicLinkTokenAction($userRepo, $tokenRepo);

    $action->execute(new VerifyMagicLinkDTO('test@example.com', '123456'));
})->throws(MagicLinkTokenExpiredException::class);

test('throws MagicLinkTokenAlreadyUsedException when token was used', function () {
    $userRepo = Mockery::mock(UserRepositoryInterface::class);
    $tokenRepo = Mockery::mock(MagicLinkTokenRepositoryInterface::class);

    $usedToken = new MagicLinkToken(
        id: 1,
        userId: 10,
        email: 'test@example.com',
        tokenHash: hash('sha256', '123456'),
        expiresAt: (new DateTimeImmutable)->modify('+10 minutes'),
        usedAt: new DateTimeImmutable,
    );

    $tokenRepo->shouldReceive('findValidToken')
        ->once()
        ->andReturn($usedToken);

    $action = new VerifyMagicLinkTokenAction($userRepo, $tokenRepo);

    $action->execute(new VerifyMagicLinkDTO('test@example.com', '123456'));
})->throws(MagicLinkTokenAlreadyUsedException::class);

test('successfully marks token as used, verifies email and returns user', function () {
    $userRepo = Mockery::mock(UserRepositoryInterface::class);
    $tokenRepo = Mockery::mock(MagicLinkTokenRepositoryInterface::class);

    $validToken = new MagicLinkToken(
        id: 1,
        userId: 10,
        email: 'test@example.com',
        tokenHash: hash('sha256', '123456'),
        expiresAt: (new DateTimeImmutable)->modify('+10 minutes'),
    );

    $domainUser = new User(
        id: 10,
        name: 'Test User',
        email: 'test@example.com',
    );

    $tokenRepo->shouldReceive('findValidToken')
        ->once()
        ->andReturn($validToken);

    $tokenRepo->shouldReceive('markAsUsed')
        ->once()
        ->with(1);

    $userRepo->shouldReceive('markEmailAsVerified')
        ->once()
        ->with(10);

    $userRepo->shouldReceive('findById')
        ->once()
        ->with(10)
        ->andReturn($domainUser);

    $action = new VerifyMagicLinkTokenAction($userRepo, $tokenRepo);

    $result = $action->execute(new VerifyMagicLinkDTO('test@example.com', '123456'));

    expect($result->id)->toBe(10);
    expect($result->email)->toBe('test@example.com');
});
