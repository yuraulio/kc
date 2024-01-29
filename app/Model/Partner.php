<?php

namespace App\Model;

use App\Model\Event;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    use MediaTrait;

    protected $table = 'partners';

    protected $fillable = [
        'name', 'url',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_partner');
    }

    public function setUrlAttribute($value)
    {
        $value = str_replace('http://', 'https://', $value);

        $this->attributes['url'] = $value;
    }
}
