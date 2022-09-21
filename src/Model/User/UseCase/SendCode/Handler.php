<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SendCode;

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
        private CodeSender $codeSender,
    ) {
    }

    public function handle(Command $command): User
    {
        $user = $this->userRepository->getById($command->id);
        $code = $user->generateNewCode();
        $user->saveCode($code);
        $this->codeSender->send($code);

        $this->flusher->flush($user);
        return $user;
    }
}
