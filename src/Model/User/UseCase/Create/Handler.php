<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Create;

use App\Exception\DomainException;
use App\Model\Flusher;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Entity\ValueObject\Id;
use DateTimeImmutable;

class Handler
{
    public function __construct(
        private Flusher $flusher,
        private UserRepository $userRepository,
    ) {
    }

    public function handle(Command $command): User
    {
        if ($this->userRepository->phoneIsBusy($command->phone)) {
            throw new DomainException('Phone is busy', 400);
        }

        $now = new DateTimeImmutable();
        $user = new User(
            createdAt: $now,
            updatedAt: $now,
            name: $command->name,
            phone: $command->phone,
            id: Id::next()
        );

        $this->userRepository->add($user);
        $this->flusher->flush();
        return $user;
    }
}
