<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Edit;

use App\Model\Flusher;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserRepository;

class Handler
{
    public function __construct(
        private Flusher $flusher,
        private UserRepository $userRepository,
    ) {
    }

    public function handle(Command $command): User
    {
        $user = $this->userRepository->getById($command->id);

        $email = $command->phone ?? $user->getPhone();
        $name = $command->name ?? $user->getName();

        $user->edit(
            $email,
            $name
        );
        $this->flusher->flush();
        return $user;
    }
}
