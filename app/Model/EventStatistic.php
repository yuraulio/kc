<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EventStatistic extends Model
{
    protected $table = 'event_statistics';

    protected $fillable = [
        'event_id',
        'user_id',
        'lastVideoSeen',
        'videos',
        'notes',
        'created_at',
        'updated_at',
        'total_seen',
        'total_duration',
        'last_seen',
    ];
}
