<?php

namespace App\DataTables\Extensions;

use Illuminate\Support\Collection;
use Yajra\DataTables\EloquentDataTable;

class AppEloquentDataTable extends EloquentDataTable
{
    protected $beforeProcessResult;

    public function setBeforeProcessResult($callback)
    {
        $this->beforeProcessResult = $callback;
    }

    /**
     * Get paginated results.
     *
     * @return \Illuminate\Support\Collection
     */
    public function results(): Collection
    {
        $results = parent::results();
        if (!$this->beforeProcessResult) {
            return $results;
        }
        $c = $this->beforeProcessResult;

        return $c($results);
    }
}
