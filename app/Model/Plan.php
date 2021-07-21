<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Model\Event;
use App\Model\Category;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'slug',
        'stripe_plan',
        'cost',
        'description'
    ];

    public function plans() {
        return $this->belongsTo('PostRider\Plan');
    }

    public function period(){
        if($this->interval == 'day'){
            return 'Daily';
        }elseif($this->interval == 'month'){
            return 'Monthly';
        }elseif($this->interval == 'year'){
            return 'Yearly';
        }
    }

    public function events(){
        return $this->belongsToMany(Event::class,'plan_events');
    }

    public function categories(){
        return $this->belongsToMany(Category::class,'plan_categories');
    }

    public function noEvents(){
        return $this->belongsToMany(Event::class,'plan_noevents');
    }
}
