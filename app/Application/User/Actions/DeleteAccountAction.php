<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Application\User\DTOs\DeleteAccountDTO;
use App\Ports\Out\Persistence\UserRepositoryInterface;

final class DeleteAccountAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(DeleteAccountDTO $dto): void
    {
        $this->userRepository->delete($dto->userId);
    }
}
