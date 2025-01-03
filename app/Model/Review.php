<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'rating',
        'status',
        'facebook_post_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'entity', 'taggables');
    }
}
