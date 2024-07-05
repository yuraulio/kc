<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Career extends Model
{
    use HasFactory;

    protected $table = 'career_paths';

    protected $fillable = [
        'name', 'priority',
    ];

    public function events(): MorphToMany
    {
        return $this->morphToMany(Event::class, 'careerpathable', 'careerpathables', 'careerpathable_id', 'event_id')
            ->withPivot('careerpath_type');
    }
}
