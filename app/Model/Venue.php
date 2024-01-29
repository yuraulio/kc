<?php

namespace App\Model;

use App\Model\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $table = 'venues';

    protected $fillable = [
        'name', 'address', 'longitude', 'latitude', 'direction_description',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_venue');
    }
}
