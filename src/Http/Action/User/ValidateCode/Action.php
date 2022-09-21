<?php

declare(strict_types=1);

namespace App\Http\Action\User\ValidateCode;

use App\Exception\DomainException;
use App\Http\Contract\User\CommonOutputContract;
use App\Model\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\PresentationBundle\Dto\Input\OutputFormat;
use Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use Symfony\PresentationBundle\Service\Presenter;
use Symfony\PresentationBundle\Service\QueryBus\Aggregate\Bus;
use Symfony\PresentationBundle\Service\QueryBus\Aggregate\Query;

class Action
{
    /**
    * @OA\Tag(name="User")
    * @OA\Response(
    *     response=200,
    *     description="Принимает от пользователя код, проверяет, совпадает ли он, и возвращает
    * ответ о валидности кода",
    *     @OA\JsonContent(
    *         allOf={
    *             @OA\Schema(ref=@Model(type=ApiFormatter::class)),
    *             @OA\Schema(
    *                 type="object",
    *                 @OA\Property(
    *                     property="data",
    *                     ref=@Model(type=CommonOutputContract::class)
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     example="200"
    *                 )
    *             )
    *         }
    *     )
    * )
    * @OA\Response(
    *     response=400,
    *     description="Bad Request"
    * ),
    * @OA\Response(
    *     response=401,
    *     description="Unauthenticated",
    *  ),
    * @OA\Response(
    *     response=403,
    *     description="Forbidden"
    * ),
    * @OA\Response(
    *     response=404,
    *     description="Resource Not Found"
    * )
    * @Security(name="Bearer")
    */
    #[Route(
        path: "/users/validateCode/{id}/{code}.{_format}",
        name: "users.validateCode",
        defaults: ["_format" => "json"],
        methods: ["GET"],
    )]
    public function action(
        string $id,
        string $code,
        Bus $bus,
        OutputFormat $outputFormat,
        Presenter $presenter,
    ): Response {
        $query = new Query(
            aggregateId: $id,
            targetEntityClass: User::class
        );

        /** @var User $user */
        $user = $bus->query($query);

        if ($user->getCode() === $code) {
            throw new DomainException('Invalid code');
        }

        return $presenter->present(
            data: ApiFormatter::prepare(
                data: CommonOutputContract::create($user),
                messages: ['Code is valid']
            ),
            outputFormat: $outputFormat
        );
    }
}
