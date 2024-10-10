<?php

namespace App\Model;

use App\Model\Event;
use App\Model\User;
use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Searchable;
use App\Services\QueryString\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MessagingActivity extends Model
{
    use HasFactory,
        Sortable,
        Filterable,
        Searchable;

    protected $guarded = ['id'];

    protected $casts = [
        'activity_log' => 'array',
    ];

    public function user()
    {
        return $this->morphedByMany(User::class, 'messaging_activityables');
    }

    public function event()
    {
        return $this->morphedByMany(Event::class, 'messaging_activityables');
    }
}
