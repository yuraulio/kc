<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;
use App\Traits\PaginateTable;
use App\Model\Delivery;
use App\Model\Category;

class Countdown extends Model
{
    use HasFactory;
    use SearchFilter;
    use PaginateTable;

    protected $table = 'cms_countdown';

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
    }

    public function delivery()
    {
        return $this->belongsToMany(Delivery::class, 'cms_countdown_delivery', 'countdown_id', 'delivery_id');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'cms_countdown_category', 'countdown_id', 'category_id');
    }
}
