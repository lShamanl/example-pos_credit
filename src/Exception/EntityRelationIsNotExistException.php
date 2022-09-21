<?php

declare(strict_types=1);

namespace App\Exception;

class EntityRelationIsNotExistException extends DomainException
{
    public function __construct()
    {
        parent::__construct("Entity relation is not exist", 404);
    }
}
