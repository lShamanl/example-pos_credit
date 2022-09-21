<?php

declare(strict_types=1);

namespace App\Http\Sms\Action\Sms\User\Delete;

use Symfony\PresentationBundle\Interfaces\InputContractInterface;

class InputContract implements InputContractInterface
{
    public string $id;
}

