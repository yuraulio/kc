<?php

namespace App\Model\Admin;

use App\Traits\PaginateTable;
use App\Traits\SearchFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
