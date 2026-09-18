<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exceptions;

use Exception;

final class InvalidMagicLinkTokenException extends Exception
{
    public function __construct(string $message = 'El código de acceso no es válido.')
    {
        parent::__construct($message);
    }
}
