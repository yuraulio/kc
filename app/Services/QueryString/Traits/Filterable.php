<?php

namespace App\Services\QueryString\Traits;

use App\Services\QueryString\Parameter\Filter;
use App\Services\QueryString\Parameter\RelationFilterParameter;
use App\Services\QueryString\Parameter\SimpleFilterParameter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $builder, Filter $filter): Builder
    {
        $callback = function (Builder $builder) use ($filter) {
            if ($filter->isDateValue()) {
                return $builder->whereDate($filter->getColumn(), $filter->getOperator(), $filter->getValue());
            }

            if ($filter->isArrayValue()) {
                return $builder->whereIn($filter->getColumn(), $filter->getValue());
            }

            return $builder->where($filter->getColumn(), $filter->getOperator(), $filter->getValue());
        };

        if ($filter instanceof RelationFilterParameter) {
            return $builder->whereHas($filter->getRelation(), $callback);
        }

        if ($filter instanceof SimpleFilterParameter) {
            return $callback($builder);
        }

        return $builder;
    }
}
