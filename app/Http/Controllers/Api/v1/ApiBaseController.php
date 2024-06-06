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

        if ($filters = $queryStringDirector->getFilters()) {
            foreach ($filters as $filter) {
                $query->filter($filter);
            }
        }

        if ($sort = $queryStringDirector->getSort()) {
            $query->sort($sort);
        }

        if ($search = $queryStringDirector->getSearch()) {
            $query->search($search);
        }

        return $query;
    }
}
