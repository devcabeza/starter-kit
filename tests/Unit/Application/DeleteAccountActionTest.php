<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\User\Actions\DeleteAccountAction;
use App\Application\User\DTOs\DeleteAccountDTO;
use App\Ports\Out\Persistence\UserRepositoryInterface;
use Mockery;

test('successfully deletes user account via repository', function () {
    $userRepo = Mockery::mock(UserRepositoryInterface::class);

    $userRepo->shouldReceive('delete')
        ->once()
        ->with(42);

    $action = new DeleteAccountAction($userRepo);

    $action->execute(new DeleteAccountDTO(userId: 42));

    expect(true)->toBeTrue();
});
