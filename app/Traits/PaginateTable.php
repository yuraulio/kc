<?php

namespace App\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use ReflectionClass;
use ReflectionMethod;

trait PaginateTable
{
    public function scopeTableSort($query, $params)
    {
        if (!isset($params->sort)) {
            return $query;
        }

        // parse table parameters column|option
        $sort = explode('|', $params->sort);
        if (is_array($sort) && count($sort) == 2) {
            $column = $sort[0] ?? $this->getDefaultSortColumn();
            $option = $sort[1] ?? $this->getDefaultSortOption();
        } else {
            $column = $this->getDefaultSortColumn();
            $option = $this->getDefaultSortOption();
        }

        if ($this->columnExists($column)) {
            return $query->orderBy($column, $option);
        }

        if (strpos($column, '.') !== false) {
            $elements = explode('.', $column);

            return $this->tryRelationshipQuery($query->getModel(), $query, $elements, $option, []);
        }
    }

    private function tryRelationshipQuery($model, $query, $elements, $option = 'asc', $parents = [])
    {
        $relation = $elements[0];
        $column = end($elements);

        $relationship = $model->relationships($relation);

        if ($relationship) {
            if ($relationship['type'] instanceof BelongsTo) {
                $model = $relationship['model'];
                $modelKey = $relationship['model_key'];
                $parent = $relationship['parent'];
                $parentKey = $relationship['parent_key'];

                array_push($parents, $parent);

                // Fix to keep all relations after join
                if (count($parents) == 1) {
                    $query = $query->select($parents[0] . '.*');
                }

                $query = $query->join($model, $model . '.' . $modelKey, '=', $parent . '.' . $parentKey);

                // If there is more then one subrelation repeat join
                if (count($elements) > 2) {
                    array_shift($elements);
                    $child = $query->getModel()->$relation()->getModel();

                    return $this->tryRelationshipQuery($child, $query, $elements, $option, $parents);
                }

                return $query->orderBy($model . '.' . $column, $option);
            } else {
                return $query;
            }
        } else {
            return $query;
        }
    }

    private function getDefaultSortColumn()
    {
        return $this->tableDefaultSortColumn ?? 'created_at';
    }

    private function getDefaultSortOption()
    {
        return $this->tableDefaultSortOption ?? 'desc';
    }

    public function scopeFilter($query, $params)
    {
        $params = $params->all();
        // parse table parameters column|option
        foreach ($params as $key => $value) {
            if (strpos($key, '|') !== false) {
                $elements = explode('|', $key);

                $this->filterRelations($query, $elements, $value);
            } else {
                if ($this->columnExists($key)) {
                    if (is_array($value)) {
                        if (is_string($value[0]) && is_object(json_decode($value[0]))
                        && is_string($value[0]) && is_object(json_decode($value[1]))) {
                            $query->whereBetween($key, [json_decode($value[0])->start, json_decode($value[1])->end]);
                        } else {
                            $query->whereIn($key, $value);
                        }
                    } elseif (is_string($value) && substr($value, 0, 2) == '!=') {
                        $query->where($key, '!=', $value);
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        }

        return $query;
    }

    public function filterRelations($query, $elements, $value)
    {
        return $query->whereHas($elements[0], function ($q) use ($elements, $value) {
            if (count($elements) > 2) {
                array_shift($elements);

                return $this->filterRelations($q, $elements, $value);
            }

            if (is_array($value)) {
                $q->whereIn(end($elements), $value);
            } elseif (is_string($value) && substr($value, 0, 2) == '!=') {
                $q->where(end($elements), '!=', $value);
            } else {
                $q->where(end($elements), $value);
            }
        });
    }

    public function columnExists($key)
    {
        if (property_exists($this, 'connection')) {
            $exists = Schema::connection($this->connection)->hasColumn($this->getTable(), $key);
        } else {
            $exists = Schema::hasColumn($this->getTable(), $key);
        }

        return $exists;
    }

    public function relationships($relation)
    {
        $model = new static;

        $relationships = [];
        foreach ((new ReflectionClass($model))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            try {
                if ($method->getName() == $relation) {
                    $return = $method->invoke($model);

                    if ($return instanceof Relation) {
                        $relationships = [
                            'type' => $return,
                            'parent' => $return->getParent()->getTable(),
                            'model' => $return->getModel()->getTable(),
                            'model_key' => $return->getOwnerKeyName(),
                            'parent_key' => $return->getForeignKeyName(),
                        ];
                    }
                }
            } catch (Exception $e) {
                throw new Exception($e);
            }
        }

        return $relationships;
    }
}
