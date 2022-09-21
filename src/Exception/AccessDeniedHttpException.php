<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class AccessDeniedHttpException extends DomainException
{
    public function __construct(
        string $message = "",
        ?int $code = 403,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, (int) $code, $previous);
    }
}
