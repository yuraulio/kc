<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

class Career extends Model
{
    use HasFactory;

    protected $table = 'career_paths';

    protected $fillable = [
        'name', 'priority'
    ];


    public function events()
    {
        return $this->belongsToMany(Event::class, 'career_event');
    }



}
