<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicAds extends Model
{
    protected $fillable = [
        'event_id',
        'headline',
        'short_description',
        'long_description',
    ];

    public $timestamps = false;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
