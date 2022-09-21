<?php

declare(strict_types=1);

namespace App\Http\Sms\Action\Sms\User\Read;

use App\Http\Sms\Contract\Sms\User\Entity\CommonOutputContract;
use App\Model\Sms\Sms\User\Entity\User;
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
    * @OA\Tag(name="Sms.Sms.User")
    * @OA\Response(
    *     response=200,
    *     description="Read query for User",
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
    *  )
    * @OA\Response(
    *     response=400,
    *     description="Bad Request"
    * ),
    * @OA\Response(
    *     response=401,
    *     description="Unauthenticated",
    * ),
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
        path: "/users/{id}.{_format}",
        name: "users.read",
        defaults: ["_format" => "json"],
        methods: ["GET"],
    )]
    public function action(
        string $id,
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

        return $presenter->present(
            data: ApiFormatter::prepare(
                CommonOutputContract::create($user)
            ),
            outputFormat: $outputFormat
        );
    }
}

