<?php

namespace App;

use App\Model\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Audience extends Model
{
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_audiences', 'audience_id', 'event_id');
    }
}
