<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;
use App\Traits\PaginateTable;

class Ticker extends Model
{
    use HasFactory;
    use SearchFilter;
    use PaginateTable;

    protected $table = 'cms_ticker';

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
    }
}
