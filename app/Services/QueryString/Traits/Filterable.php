<?php

namespace App\Services\QueryString\Traits;

use App\Services\QueryString\Components\Filter;
use App\Services\QueryString\Components\RelationFilter;
use App\Services\QueryString\Components\SimpleFilter;
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

        if ($filter instanceof RelationFilter) {
            return $builder->whereHas($filter->getRelation(), $callback);
        }

        if ($filter instanceof SimpleFilter) {
            return call_user_func($callback);
        }

        return $builder;
    }
}
