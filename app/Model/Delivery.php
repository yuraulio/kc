<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

use App\Traits\SlugTrait;

class Delivery extends Model
{
    use HasFactory;
    use SlugTrait;

    protected $table = 'deliveries';

    protected $fillable = [
        'name'
    ];


    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_delivery');
    }
}
