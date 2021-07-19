<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

class Slug extends Model
{
    use HasFactory;

      /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'slugable_type',
        'slugable_id',
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function slugable()
    {
        return $this->morphTo();
    }

}
