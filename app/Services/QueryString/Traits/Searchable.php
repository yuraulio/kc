<?php

namespace App\Services\QueryString\Traits;

use App\Services\QueryString\Parameter\SearchParameter;
use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public function scopeSearch(Builder $builder, SearchParameter $search): Builder
    {
        return $builder->where(function (Builder $query) use ($search) {
            $searchableFields = [
                'firstname',
                'lastname',
                'email',
                'company',
                'job_title',
                'kc_id',
            ];

            foreach ($searchableFields as $searchableField) {
                $query->orWhere($searchableField, 'LIKE', $search->getTerm());
            }
        });
    }
}
