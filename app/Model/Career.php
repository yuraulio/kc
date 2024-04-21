<?php

namespace App\Model;

use App\Model\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;

    protected $table = 'career_paths';

    protected $fillable = [
        'name', 'priority',
    ];

    public function events()
    {
        return $this->morphToMany(Event::class, 'careerpathable', 'careerpathables', 'careerpathable_id', 'event_id')
            ->withPivot('careerpath_type');
    }
}
