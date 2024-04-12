<?php

namespace App\Services\QueryString\Builders;

use App\Services\QueryString\Components\SimpleFilter;

class SimpleFilterBuilder
{

    public static function build(string $columnName): SimpleFilter
    {
        $filter = new SimpleFilter();
        $filter->setColumn($columnName);

        return $filter;
    }

}
