<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'title', 'subtitle', 'type', 'features'
    ];


    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tickets')->withPivot('features','priority', 'quantity', 'price', 'options');
    }

}
