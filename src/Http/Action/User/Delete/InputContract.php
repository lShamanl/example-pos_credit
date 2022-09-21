<?php

declare(strict_types=1);

namespace App\Http\Action\User\Delete;

use App\Model\User\Entity\ValueObject\Id;
use App\Model\User\UseCase\Delete\Command;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\PresentationBundle\Interfaces\InputContractInterface;

class InputContract implements InputContractInterface
{
    #[NotNull]
    #[Uuid]
    public string $id;

    public function createCommand(): Command
    {
        return new Command(
            id: new Id($this->id),
        );
    }
}
