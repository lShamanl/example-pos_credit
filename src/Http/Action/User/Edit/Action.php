<?php

declare(strict_types=1);

namespace App\Http\Action\User\Edit;

use App\Http\Contract\User\CommonOutputContract;
use App\Model\User\UseCase\Edit\Handler;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\PresentationBundle\Dto\Input\OutputFormat;
use Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use Symfony\PresentationBundle\Service\Presenter;

class Action
{
    /**
    * @OA\Tag(name="User")
    * @OA\Post(
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 ref=@Model(type=InputContract::class)
    *             )
    *         )
    *     )
    * )
    * @OA\Response(
    *     response=200,
    *     description="Update command for User",
    *     @OA\JsonContent(
    *          allOf={
    *              @OA\Schema(ref=@Model(type=ApiFormatter::class)),
    *              @OA\Schema(type="object",
    *                  @OA\Property(
    *                      property="data",
    *                      ref=@Model(type=CommonOutputContract::class)
    *                  ),
    *                  @OA\Property(
    *                      property="status",
    *                      example="200"
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
        path: "/users/edit.{_format}",
        name: "users.edit",
        defaults: ["_format" => "json"],
        methods: ["POST"],
    )]
    public function action(
        OutputFormat $outputFormat,
        Presenter $presenter,
        InputContract $contract,
        Handler $handler,
    ): Response {
        $user = $handler->handle(
            $contract->createCommand()
        );

        return $presenter->present(
            data: ApiFormatter::prepare(
                data: CommonOutputContract::create($user),
                messages: ['User edited']
            ),
            outputFormat: $outputFormat
        );
    }
}
