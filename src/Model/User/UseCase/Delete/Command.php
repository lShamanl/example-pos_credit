<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Delete;

use App\Model\User\Entity\ValueObject\Id;

class Command
{
    public function __construct(
        public readonly Id $id,
    ) {
    }
}
