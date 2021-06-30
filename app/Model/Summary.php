<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;
use App\Model\Media;
use App\Traits\MediaTrait;

class Summary extends Model
{
    use HasFactory, MediaTrait;

    protected $table = 'summary_events';

    protected $fillable = [
        'title', 'description', 'section'
    ];

    public function event()
    {
        return $this->belongsToMany(Event::class, 'events_summaryevent', 'summary_event_id','event_id' );
    }

    public function medias()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
