<?php

declare(strict_types=1);

namespace App\Http\Contract\User;

use App\Model\User\Entity\User;
use DateTimeInterface;

class CommonOutputContract
{
    public string $id;
    public string $createdAt;
    public string $updatedAt;
    public string $name;
    public string $code;
    public string $phone;

    public static function create(User $user): self
    {
        $contract = new self();
        $contract->id = $user->getId()->getValue();
        $contract->createdAt = $user->getCreatedAt()->format(DateTimeInterface::ATOM);
        $contract->updatedAt = $user->getUpdatedAt()->format(DateTimeInterface::ATOM);
        $contract->name = $user->getName();
        $contract->phone = $user->getPhone();
        $contract->code = $user->getCode();

        return $contract;
    }
}
