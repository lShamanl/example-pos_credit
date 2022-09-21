<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\AggregateRoot;
use App\Model\EventsTrait;
use App\Model\User\Entity\Type\IdType;
use App\Model\User\Entity\ValueObject\Id as UserId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "sms_users")]
#[Entity(repositoryClass: UserRepository::class)]
#[HasLifecycleCallbacks]
class User implements AggregateRoot
{
    use EventsTrait;

    /** ID entity property */
    #[Id]
    #[Column(
        type: IdType::NAME,
        nullable: false,
    )]
    private UserId $id;

    /** Created at entity stamp */
    #[Column(
        type: "datetime_immutable",
        nullable: false,
    )]
    private DateTimeImmutable $createdAt;

    /** Updated at entity stamp */
    #[Column(
        type: "datetime_immutable",
        nullable: false,
    )]
    private DateTimeImmutable $updatedAt;

    #[Column(
        type: "string",
        nullable: false,
    )]
    private string $name;

    #[Column(
        type: "string",
        length: 5,
        nullable: true,
    )]
    private ?string $code = null;

    #[Column(
        type: "string",
        nullable: false,
    )]
    private string $phone;

    public function __construct(
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        string $name,
        string $phone,
        UserId $id,
    ) {
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->name = $name;
        $this->phone = $phone;
        $this->id = $id;
    }

    public function generateNewCode(): string
    {
        return (string) random_int(10000, 99999);
    }

    public function edit(
        string $name,
        ?string $code,
    ): void {
        $this->name = $name;
        $this->code = $code;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    #[PreUpdate]
    public function onUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function saveCode(string $code): void
    {
        $this->code = $code;
    }
}

