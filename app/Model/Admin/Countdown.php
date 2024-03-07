<?php

namespace App\Model\Admin;

use App\Model\Category;
use App\Model\Delivery;
use App\Model\Event;
use App\Traits\PaginateTable;
use App\Traits\SearchFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function events()
    {
        return $this->belongsToMany(Event::class, 'cms_countdown_event', 'countdown_id', 'event_id');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'cms_countdown_category', 'countdown_id', 'category_id');
    }
    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'cms_countdown_delivery', 'countdown_id', 'delivery_id');
    }
}
