<?php

namespace App\Services\QueryString\Builders;

use App\Services\QueryString\Parameter\SimpleFilterParameter;

class SimpleFilterBuilder
{
    public static function build(string $columnName): SimpleFilterParameter
    {
        $filter = new SimpleFilterParameter();
        $filter->setColumn($columnName);

        return $filter;
    }
}
