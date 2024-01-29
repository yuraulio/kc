<?php

namespace App\Model;

use App\Model\Event;
use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartCache extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventt()
    {
        return $this->belongsTo(Event::class, 'event', 'id');
    }
}
