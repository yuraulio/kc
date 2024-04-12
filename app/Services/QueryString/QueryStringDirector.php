<?php

namespace App\Services\QueryString;

use App\Services\QueryString\Builders\RelationFilterBuilder;
use App\Services\QueryString\Builders\SimpleFilterBuilder;
use App\Services\QueryString\Components\RelationFilter;
use App\Services\QueryString\Components\Search;
use App\Services\QueryString\Components\SimpleFilter;
use App\Services\QueryString\Components\Sort;
use App\Services\QueryString\Enums\Direction;
use App\Services\QueryString\Enums\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class QueryStringDirector
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getSort(): ?Sort
    {
        if ($this->request->query->has('sort')) {
            $sortBy = $this->request->query->get('sort');

            $sort = new Sort();
            $sort->setColumn(Str::replaceFirst('-', '', $sortBy));
            $sort->setDirection(Str::startsWith($sortBy, '-') ? Direction::DESC : Direction::ASC);

            return $sort;
        }

        return null;
    }

    public function getFilters(): array
    {
        $filters = [];

        if ($this->request->has('filter')) {
            foreach ($this->request->query->all('filter') as $key => $values) {
                if (!is_array($values)) {
                    $values = [
                        Operator::eq->name => $values,
                    ];
                }

                foreach ($values as $operator => $value) {
                    $filter = Str::contains($key, '.')
                        ? RelationFilterBuilder::build($key)
                        : SimpleFilterBuilder::build($key);

                    if ($operator = Operator::byName($operator)) {
                        $filter->setOperator($operator->value);
                    } else {
                        // TODO: throw an exception
                        continue;
                    }

                    if (is_scalar($value)) {
                        $filter->setValue($this->parseValue($value));
                    } else {
                        // TODO: throw an exception
                        continue;
                    }

                    $filters[] = $filter;
                }
            }
        }

        return $filters;
    }

    public function getSearch(): ?Search
    {
        if ($this->request->query->has('search')) {
            $search = $this->request->query->get('search');

            $newSearch = new Search();
            $newSearch->setTerm(sprintf('%%%s%%', $search));

            return $newSearch;
        }

        return null;
    }

    private function parseValue(string $value): string|array
    {
        $value = explode(',', $value);

        if (count($value) > 1) {
            return $value;
        }

        return array_shift($value);
    }
}
