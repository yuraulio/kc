<?php

namespace App\Model;

use App\Model\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public const FREE = 822;

    protected $table = 'tickets';

    protected $fillable = [
        'title', 'subtitle', 'status', 'type', 'features', 'public_title',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tickets')
            ->withPivot('features', 'priority', 'quantity', 'price', 'options', 'public_title', 'seats_visible');
    }
}
