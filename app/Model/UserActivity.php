<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function who(): BelongsTo
    {
        return $this->belongsTo(User::class, 'who_id');
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
