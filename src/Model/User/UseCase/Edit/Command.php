<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Edit;

use App\Model\User\Entity\ValueObject\Id;

class Command
{
    public function __construct(
        public readonly Id $id,
        public readonly string $name,
        public readonly string $phone,
    ) {
    }
}
