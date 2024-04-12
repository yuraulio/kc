<?php

namespace App\Services\QueryString\Builders;

use App\Services\QueryString\Components\RelationFilter;
use Illuminate\Support\Str;

class RelationFilterBuilder
{

    public static function build(string $columnName): RelationFilter
    {
        $column = Str::afterLast($columnName, '.');
        $relation = Str::beforeLast($columnName, '.');

        if ($column === 'id') {
            $column = $columnName;
        }

        if ($relation === 'role') {
            $column = 'roles.id';
        }

        $filter = new RelationFilter();
        $filter->setRelation($relation);
        $filter->setColumn($column);

        return $filter;
    }

}
