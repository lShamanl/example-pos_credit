<?php

declare(strict_types=1);

namespace App\Exception;

class EntityRelationAlreadyExistException extends DomainException
{
    public function __construct()
    {
        parent::__construct("Entity relation already exist", 404);
    }
}
