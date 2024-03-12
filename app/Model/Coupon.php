<?php

namespace App\Model;

use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory, Filterable, Sortable;

    protected $fillable = [
        'used',
    ];

    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_coupons');
    }
}
