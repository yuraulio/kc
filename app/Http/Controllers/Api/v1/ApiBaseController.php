<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\QueryString\QueryStringDirector;
use App\Traits\JsonResponseTrait;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    use JsonResponseTrait;

    const PER_PAGE = 50;

    protected function applyRequestParametersToQuery(BuilderContract $query, Request $request): BuilderContract
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

    protected function applyRequestParametersToModel(Model $model, Request $request): Model
    {
        $queryStringDirector = new QueryStringDirector($request);

        if ($queryStringDirector->hasIncludeParameter()) {
            $model->load($queryStringDirector->getInclude());
        }

        return $model;
    }

    protected function paginateByRequestParameters(BuilderContract $query, Request $request): LengthAwarePaginator
    {
        return $query->paginate((int) $request->query->get('per_page', self::PER_PAGE))
            ->appends($request->query->all());
    }
}
