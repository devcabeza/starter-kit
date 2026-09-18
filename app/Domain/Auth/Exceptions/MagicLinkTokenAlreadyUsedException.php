<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exceptions;

use Exception;

final class MagicLinkTokenAlreadyUsedException extends Exception
{
    public function __construct(string $message = 'Este código de acceso ya ha sido utilizado.')
    {
        parent::__construct($message);
    }
}
