<?php

namespace App\Model;

use App\Model\Category;
use App\Model\Event;
use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory, Filterable, Sortable;

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'slug',
        'stripe_plan',
        'cost',
        'description',
    ];

    public function plans()
    {
        return $this->belongsTo('PostRider\Plan');
    }

    public function period()
    {
        if ($this->interval == 'day') {
            return 'Daily';
        } elseif ($this->interval == 'month') {
            return 'Monthly';
        } elseif ($this->interval == 'year') {
            return 'Yearly';
        }
    }

    public function getDays()
    {
        if ($this->interval == 'day') {
            return $this->interval;
        } elseif ($this->interval == 'month') {
            return $this->interval * 30;
        } elseif ($this->interval == 'year') {
            return 365;
        }
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'plan_events');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'plan_categories');
    }

    public function noEvents()
    {
        return $this->belongsToMany(Event::class, 'plan_noevents');
    }
}
