<?php

namespace App\Services\QueryString\Traits;

use App\Services\QueryString\Components\Search;
use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public function scopeSearch(Builder $builder, Search $search): Builder
    {
        return $builder->where(function (Builder $query) use ($search) {
            $query->orWhere('firstname', 'LIKE', $search->getTerm())
                ->orWhere('lastname', 'LIKE', $search->getTerm())
                ->orWhere('email', 'LIKE', $search->getTerm())
                ->orWhere('company', 'LIKE', $search->getTerm())
                ->orWhere('job_title', 'LIKE', $search->getTerm());
        });
    }
}
