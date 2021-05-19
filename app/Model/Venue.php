<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

class Venue extends Model
{
    use HasFactory;

    protected $table = 'venues';

    protected $fillable = [
        'name', 'address', 'longitude', 'latitude'
    ];


    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_venue');
    }
}
