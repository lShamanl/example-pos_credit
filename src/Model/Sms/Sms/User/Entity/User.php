<?php

declare(strict_types=1);

namespace App\Model\Sms\Sms\User\Entity;

use App\Model\AggregateRoot;
use App\Model\EventsTrait;
use App\Model\Sms\Sms\User\Entity\Type\IdType;
use App\Model\Sms\Sms\User\Entity\ValueObject\Id as UserId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "sms_users")]
#[Entity(repositoryClass: UserRepository::class)]
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
        nullable: true,
    )]
    private string $code;

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

    /**
     * todo: please implementation method sendCode, description: Отправляет пользователю новый код, сохраняет его в
     * модели User
     */
    public function sendCode(): void
    {

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

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}

