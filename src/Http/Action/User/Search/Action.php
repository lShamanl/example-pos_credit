<?php

declare(strict_types=1);

namespace App\Http\Action\User\Search;

use App\Http\Contract\User\CommonOutputContract;
use App\Model\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\PresentationBundle\Dto\Input\OutputFormat;
use Symfony\PresentationBundle\Dto\Input\SearchQuery;
use Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use Symfony\PresentationBundle\Dto\Output\OutputPagination;
use Symfony\PresentationBundle\Service\Presenter;
use Symfony\PresentationBundle\Service\QueryBus\Search\Bus;
use Symfony\PresentationBundle\Service\QueryBus\Search\Query;

class Action
{
    /**
    * @OA\Tag(name="User")
    * @OA\Get(
    *     @OA\Parameter(
    *          name="searchQuery",
    *          in="query",
    *          required=false,
    *          @OA\Schema(
    *              ref=@Model(type=QueryParams::class)
    *          ),
    *     )
    * )
    * @OA\Response(
    *     response=200,
    *     description="Search query for User",
    *     @OA\JsonContent(
    *          allOf={
    *              @OA\Schema(ref=@Model(type=ApiFormatter::class)),
    *              @OA\Schema(
    *                  type="object",
    *                  @OA\Property(
    *                      property="data",
    *                      type="object",
    *                      @OA\Property(
    *                          property="data",
    *                          ref=@Model(type=CommonOutputContract::class),
    *                          type="object"
    *                      ),
    *                      @OA\Property(
    *                          property="pagination",
    *                          ref=@Model(type=OutputPagination::class),
    *                          type="object"
    *                      )
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
        path: "/users.{_format}",
        name: "users.search",
        defaults: ["_format" => "json"],
        methods: ["GET"],
    )]
    public function action(
        SearchQuery $searchQuery,
        Bus $bus,
        OutputFormat $outputFormat,
        Presenter $presenter,
    ): Response {
        $query = new Query(
            targetEntityClass: User::class,
            pagination: $searchQuery->pagination,
            filters: $searchQuery->filters,
            sorts: $searchQuery->sorts
        );

        $searchResult = $bus->query($query);
        return $presenter->present(
            data: ApiFormatter::prepare([
                'data' => array_map(static function (User $user) {
                    return CommonOutputContract::create($user);
                }, $searchResult->entities),
                'pagination' => $searchResult->pagination
            ]),
            outputFormat: $outputFormat
        );
    }
}
