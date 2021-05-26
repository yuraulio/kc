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

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];


    public function events()
    {
        return $this->morphedByMany(Event::class, 'benefitable', 'benefitables');
    }

    public function pages()
    {
        return $this->morphedMany(Event::class, 'benefitable', 'benefitables');
    }
}
