<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class LogicalException extends DomainException
{
    public function __construct(
        string $message = "",
        ?int $code = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, (int) $code, $previous);
    }
}
