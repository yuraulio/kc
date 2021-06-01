<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'name',
        'menuable_id',
        'menuable_type'
    ];

    public function menuable()
    {
        return $this->morphTo();
    }
}
