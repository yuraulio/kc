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
use Illuminate\Support\Collection;

/**
 * The base controller for the API.
 *
 * All API controllers should extend this class.
 */
abstract class ApiBaseController extends Controller
{
    use JsonResponseTrait;

    /**
     * The number of items per page.
     */
    public const PER_PAGE = 50;

    /**
     * Apply the request parameters to the query.
     *
     * @deprecated should be avoided in favor native Eloquent methods.
     */
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

    /**
     * Apply the request parameters to the model.
     *
     * @deprecated should be avoided in favor native Eloquent methods.
     */
    protected function applyRequestParametersToModel(Model $model, Request $request): Model
    {
        $queryStringDirector = new QueryStringDirector($request);

        if ($queryStringDirector->hasIncludeParameter()) {
            $model->load($queryStringDirector->getInclude());
        }

        return $model;
    }

    /**
     * Paginate the query by the request parameters.
     *
     * @deprecated should be avoided in favor native Eloquent methods.
     */
    protected function paginateByRequestParameters(BuilderContract $query, Request $request): LengthAwarePaginator|Collection
    {
        $perPage = $request->query->get('per_page', self::PER_PAGE);

        if ($perPage !== 'all') {
            return $query->paginate((int) $perPage)->appends($request->query->all());
        }

        return $query->get();
    }
}
