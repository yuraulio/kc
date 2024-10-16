<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    public $timestamps = false;

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class);
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'entity', 'taggables');
    }
}
