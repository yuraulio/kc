<?php

namespace App\Services\QueryString;

use App\Services\QueryString\Builders\RelationFilterBuilder;
use App\Services\QueryString\Builders\SimpleFilterBuilder;
use App\Services\QueryString\Enums\Direction;
use App\Services\QueryString\Enums\Operator;
use App\Services\QueryString\Parameter\SearchParameter;
use App\Services\QueryString\Parameter\SortParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class QueryStringDirector
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function hasSortParameter(): bool
    {
        return $this->request->has('sort');
    }

    public function getSort(): ?SortParameter
    {
        $sortBy = $this->request->query->get('sort');

        if (empty($sortBy)) {
            return null;
        }

        $sort = new SortParameter();
        $sort->setColumn(Str::replaceFirst('-', '', $sortBy));
        $sort->setDirection(Str::startsWith($sortBy, '-') ? Direction::DESC : Direction::ASC);

        return $sort;
    }

    public function hasFilterParameter(): bool
    {
        return $this->request->has('filter');
    }

    public function getFilters(): array
    {
        $filters = [];

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

        return $filters;
    }

    public function hasSearchParameter(): bool
    {
        return  $this->request->has('search');
    }

    public function getSearch(): ?SearchParameter
    {
        $search = $this->request->query->get('search');

        if (empty($search)) {
            return null;
        }

        $newSearch = new SearchParameter();
        $newSearch->setTerm(sprintf('%%%s%%', $search));

        return $newSearch;
    }

    public function hasIncludeParameter(): bool
    {
        return $this->request->has('include');
    }

    public function getInclude(): array
    {
        $includes = $this->request->query->get('include');

        return explode(',', $includes);
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
