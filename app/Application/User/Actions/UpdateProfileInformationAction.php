<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Application\User\DTOs\UpdateProfileDTO;
use App\Domain\User\Exceptions\EmailAlreadyInUseException;
use App\Domain\User\Models\User;
use App\Ports\Out\Persistence\UserRepositoryInterface;

final class UpdateProfileInformationAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(UpdateProfileDTO $dto): User
    {
        $normalizedEmail = strtolower(trim($dto->email));
        $normalizedName = trim($dto->name);

        if ($this->userRepository->emailExistsExceptUser($normalizedEmail, $dto->userId)) {
            throw EmailAlreadyInUseException::forEmail($normalizedEmail);
        }

        return $this->userRepository->updateProfile(
            id: $dto->userId,
            name: $normalizedName,
            email: $normalizedEmail,
        );
    }
}
