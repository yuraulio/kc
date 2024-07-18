<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventTicket extends Pivot
{
    protected $table = 'event_tickets';

    protected $casts = [
        'options' => 'array',
        'features' => 'array',
    ];
}
