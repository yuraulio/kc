<?php

namespace App\Model;

use App\Model\Event;
use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 */
class City extends Model
{
    use HasFactory;
    use SlugTrait;

    protected $table = 'cities';

    protected $fillable = [
        'name',
        'country_id',
    ];

    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_city');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
