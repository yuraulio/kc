<?php

namespace App\Model;

use App\Model\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'used',
    ];

    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_coupons');
    }
}
