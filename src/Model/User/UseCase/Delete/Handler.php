<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Delete;

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

        $this->userRepository->remove($user);
        $this->flusher->flush($user);
        return $user;
    }
}
