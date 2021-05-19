<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

class Benefit extends Model
{
    use HasFactory;

    protected $table = 'benefits';

    protected $fillable = [
        'name', 'description', 'priority'
    ];


    public function events()
    {
        return $this->morphedMany(Event::class, 'benefitable', 'benefitables');
    }
}
