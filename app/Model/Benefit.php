<?php

namespace App\Model;

use App\Model\Event;
use App\Model\Media;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use HasFactory, MediaTrait;

    protected $table = 'benefits';

    protected $fillable = [
        'name', 'description', 'priority',
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

    public function medias()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
