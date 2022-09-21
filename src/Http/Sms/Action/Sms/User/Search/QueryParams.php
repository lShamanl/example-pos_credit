<?php

declare(strict_types=1);

namespace App\Http\Sms\Action\Sms\User\Search;

use OpenApi\Annotations as OA;
use Symfony\PresentationBundle\Dto\Input\Filters;
use Symfony\PresentationBundle\Dto\Input\SearchQuery;

class QueryParams
{

    /**
    * @OA\Property(
    *     property="filter",
    *     type="object",
    *     example={
    *         "id": {"eq": "1dbd2bfa-2904-45de-b60b-8a58f4a3dc55"},
    *         "createdAt": {"range": "2020-01-01 00:00:00,2020-12-31 23:59:59"},
    *         "updatedAt": {"range": "2020-01-01 00:00:00,2020-12-31 23:59:59"},
    *         "name": {"eq": "bar"},
    *         "code": {"eq": "bar"},
    *         "phone": {"eq": "bar"}
    *     }
    * )
    */
    public Filters $filters;
}

