<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exceptions;

use Exception;

final class TooManyMagicLinkRequestsException extends Exception
{
    public function __construct(string $message = 'Demasiados intentos. Por favor espera un momento antes de volver a intentar.')
    {
        parent::__construct($message);
    }
}
