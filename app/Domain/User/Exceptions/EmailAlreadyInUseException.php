<?php

declare(strict_types=1);

namespace App\Domain\User\Exceptions;

use DomainException;

final class EmailAlreadyInUseException extends DomainException
{
    public static function forEmail(string $email): self
    {
        return new self("El correo electrónico '{$email}' ya está registrado por otra cuenta.");
    }
}
