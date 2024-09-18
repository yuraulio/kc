<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    public $timestamps = false;

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class);
    }
}
