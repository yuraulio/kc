<?php

namespace App\Services\QueryString\Traits;

use App\Services\QueryString\Parameter\SortParameter;
use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeSort(Builder $builder, SortParameter $sort): Builder
    {
        return $builder->orderBy($sort->getColumn(), $sort->getDirection());
    }
}
