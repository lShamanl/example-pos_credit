<?php

declare(strict_types=1);

namespace App\Http\Sms\Action\Sms\User\SendCode;

use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\PresentationBundle\Interfaces\InputContractInterface;

class InputContract implements InputContractInterface
{
    #[NotNull]
    #[Uuid]
    public string $id;
}
