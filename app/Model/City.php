<?php

namespace App\Model;

use App\Model\Event;
use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    use SlugTrait;

    protected $table = 'cities';

    protected $fillable = [
        'name',
    ];

    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_city');
    }
}
