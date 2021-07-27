<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'used'
    ];

    public function event()
    {
        return $this->belongsToMany(Event::class,'event_coupons');
    }
}
