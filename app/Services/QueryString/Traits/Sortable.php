<?php

namespace App\Services\QueryString\Traits;

use App\Services\QueryString\Components\Sort;
use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeSort(Builder $builder, Sort $sort): Builder
    {
        return $builder->orderBy($sort->getColumn(), $sort->getDirection());
    }

}
