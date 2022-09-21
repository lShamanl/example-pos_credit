<?php

declare(strict_types=1);

namespace App\Http\Sms\Action\Sms\User\Edit;

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
}
