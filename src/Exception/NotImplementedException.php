<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Данное исключение является заглушкой для не реализованных методов
 */
class NotImplementedException extends DomainException
{
    public function __construct(string $message = 'Method not yet implemented')
    {
        parent::__construct($message, 500, null);
    }
}
