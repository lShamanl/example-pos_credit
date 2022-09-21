<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Create;

class Command
{
    public function __construct(
        public readonly string $name,
        public readonly string $phone,
    ) {
    }
}
