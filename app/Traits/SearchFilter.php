<?php

namespace App\Traits;

use Laravel\Scout\Searchable;

trait SearchFilter
{
    use Searchable;

    /**
     * Looks for filter and load properly query builder.
     */
    public function scopeLookFor($query, $filter)
    {
        if (is_null($filter) or empty($filter)) {
            return $query;
        }

        return $this->search($filter)->query(function ($q) {
            $q->withoutGlobalScopes();
        });
    }

    /**
     * Looks for filter and load properly query builder.
     */
    public function scopeLookForOriginal($query, $filter, $pk = 'id', $column = 'id')
    {
        if (is_null($filter) or empty($filter)) {
            return $query;
        }

        return $query->whereIn($pk, $this->lookFor($filter)->get()->pluck($column)->flatten());
    }
}
