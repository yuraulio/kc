<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;
use App\Model\Lesson;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types';

    protected $fillable = [
        'name', 'description', 'parent'
    ];

    public function events()
    {
        return $this->morphedByMany(Event::class, 'typeables');
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class, 'typeables');
    }
}
