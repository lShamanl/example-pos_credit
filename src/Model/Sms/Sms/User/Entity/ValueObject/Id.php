<?php

declare(strict_types=1);

namespace App\Model\Sms\Sms\User\Entity\ValueObject;

use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::uuid($value);
        $this->value = $value;
    }

    public static function next(): self
    {
        return new self(Uuid::v4()->__toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

