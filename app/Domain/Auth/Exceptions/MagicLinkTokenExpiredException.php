<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exceptions;

use Exception;

final class MagicLinkTokenExpiredException extends Exception
{
    public function __construct(string $message = 'El código de acceso ha expirado. Por favor solicita uno nuevo.')
    {
        parent::__construct($message);
    }
}
