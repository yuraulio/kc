<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;
use App\Traits\MediaTrait;

class Partner extends Model
{
    use HasFactory;
    use MediaTrait;
    
    protected $table = 'partners';

    protected $fillable = [
        'name'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_partner');
    }
}
