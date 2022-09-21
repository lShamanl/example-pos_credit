<?php

declare(strict_types=1);

namespace App\Http\Action\User\Create;

use App\Model\User\UseCase\Create\Command;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\PresentationBundle\Interfaces\InputContractInterface;

class InputContract implements InputContractInterface
{
    #[NotNull]
    #[Length(max: 255)]
    public string $name;

    #[NotNull]
    #[Length(max: 255)]
    public string $phone;

    public function createCommand(): Command
    {
        return new Command(
            name: $this->name,
            phone: $this->phone,
        );
    }
}
