<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

class Summary extends Model
{
    use HasFactory;

    protected $table = 'summary_events';

    protected $fillable = [
        'title', 'description'
    ];

    public function event()
    {
        return $this->belongsToMany(Event::class, 'events_summaryevent', 'summary_event_id','event_id' );
    }
}
