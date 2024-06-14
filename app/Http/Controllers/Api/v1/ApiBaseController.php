<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\QueryString\QueryStringDirector;
use App\Traits\JsonResponseTrait;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    use JsonResponseTrait;

    protected function applyRequestParametersToQuery(Request $request, BuilderContract $query): BuilderContract
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

    protected function applyRequestParametersToModel(Request $request, Model $model): Model
    {
        $queryStringDirector = new QueryStringDirector($request);

        if ($queryStringDirector->hasIncludeParameter()) {
            $model->load($queryStringDirector->getInclude());
        }

        return $model;
    }

}
