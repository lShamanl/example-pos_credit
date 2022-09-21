<?php

declare(strict_types=1);

namespace App\Http\Sms\Action\Sms\User\Create;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\PresentationBundle\Interfaces\InputContractInterface;

class InputContract implements InputContractInterface
{
    #[NotNull]
    #[Length(max: 255)]
    public string $name;
}

