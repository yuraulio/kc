<?php

namespace App\Traits;

trait PaginateTable
{
    public function scopeTableSort($query, $params)
    {
        // parse table parameters column|option
        $pieces = $params ? explode("|", $params) : [];
        $column = array_get($pieces, 0, $this->getDefaultSortColumn());
        $option = array_get($pieces, 1, $this->getDefaultSortOption());

        return $query->orderBy($column, $option);
    }

    private function getDefaultSortColumn()
    {
        return $this->tableDefaultSortColumn ?? 'created_at';
    }

    private function getDefaultSortOption()
    {
        return $this->tableDefaultSortOption ?? 'desc';
    }
}
