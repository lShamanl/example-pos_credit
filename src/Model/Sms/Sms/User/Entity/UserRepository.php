<?php

declare(strict_types=1);

namespace App\Model\Sms\Sms\User\Entity;

use App\Exception\EntityNotExistException;
use App\Model\Sms\Sms\User\Entity\User;
use App\Model\Sms\Sms\User\Entity\ValueObject\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<User> */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function remove(User $user): void
    {
        $this->getEntityManager()->remove($user);
    }

    public function getById(Id $id): User
    {
        if (!$user = $this->findOneBy(['id' => $id->getValue()])) {
            throw new EntityNotExistException("User with Id {$id->getValue()} not exist");
        }
        /** @var User $user */
        return $user;
    }
}

