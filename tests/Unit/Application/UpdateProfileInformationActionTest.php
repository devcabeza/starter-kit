<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\User\Actions\UpdateProfileInformationAction;
use App\Application\User\DTOs\UpdateProfileDTO;
use App\Domain\User\Exceptions\EmailAlreadyInUseException;
use App\Domain\User\Models\User;
use App\Ports\Out\Persistence\UserRepositoryInterface;
use Mockery;

test('successfully updates user profile information', function () {
    $userRepo = Mockery::mock(UserRepositoryInterface::class);

    $updatedUser = new User(
        id: 1,
        name: 'Nuevo Nombre',
        email: 'nuevo@example.com',
    );

    $userRepo->shouldReceive('emailExistsExceptUser')
        ->once()
        ->with('nuevo@example.com', 1)
        ->andReturn(false);

    $userRepo->shouldReceive('updateProfile')
        ->once()
        ->with(1, 'Nuevo Nombre', 'nuevo@example.com')
        ->andReturn($updatedUser);

    $action = new UpdateProfileInformationAction($userRepo);

    $result = $action->execute(new UpdateProfileDTO(
        userId: 1,
        name: '  Nuevo Nombre  ',
        email: 'NUEVO@EXAMPLE.COM ',
    ));

    expect($result->name)->toBe('Nuevo Nombre')
        ->and($result->email)->toBe('nuevo@example.com');
});

test('throws EmailAlreadyInUseException when email belongs to another user', function () {
    $userRepo = Mockery::mock(UserRepositoryInterface::class);

    $userRepo->shouldReceive('emailExistsExceptUser')
        ->once()
        ->with('existente@example.com', 1)
        ->andReturn(true);

    $userRepo->shouldNotReceive('updateProfile');

    $action = new UpdateProfileInformationAction($userRepo);

    $action->execute(new UpdateProfileDTO(
        userId: 1,
        name: 'Nuevo Nombre',
        email: 'existente@example.com',
    ));
})->throws(EmailAlreadyInUseException::class);
