<?php

declare(strict_types=1);

namespace App\Http\Sms\Contract\Sms\User\Entity;

class CommonOutputContract
{
    public string $id;
    public string $createdAt;
    public string $updatedAt;
    public string $name;
    public string $code;
    public string $phone;

    public static function create(): self
    {
        $contract = new self();
        
        
        return $contract;
    }
}

