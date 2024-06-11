<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\QueryString\QueryStringDirector;
use App\Traits\JsonResponseTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    use JsonResponseTrait;

    protected function applyRequestParametersToQuery(Request $request, Builder $query): Builder
    {
        $queryStringDirector = new QueryStringDirector($request);

        if ($queryStringDirector->hasFilterParameter()) {
            foreach ($queryStringDirector->getFilters() as $filter) {
                $query->filter($filter);
            }
        }

        if ($queryStringDirector->hasSortParameter()) {
            $query->sort($queryStringDirector->getSort());
        }

        if ($queryStringDirector->hasSearchParameter()) {
            $query->search($queryStringDirector->getSearch());
        }

        if ($queryStringDirector->hasIncludeParameter()) {
            $query->with($queryStringDirector->getInclude());
        }

        return $query;
    }
}
