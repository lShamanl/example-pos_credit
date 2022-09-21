<?php

declare(strict_types=1);

namespace App\Http\Action\User\Edit;

use App\Model\User\Entity\ValueObject\Id;
use App\Model\User\UseCase\Edit\Command;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\PresentationBundle\Interfaces\InputContractInterface;

class InputContract implements InputContractInterface
{
    #[NotNull]
    #[Uuid]
    public string $id;

    #[NotNull]
    #[Length(max: 255)]
    public string $name;

    #[NotNull]
    #[Length(max: 255)]
    public string $phone;

    public function createCommand(): Command
    {
        return new Command(
            id: new Id($this->id),
            name: $this->name,
            phone: $this->phone,
        );
    }
}
