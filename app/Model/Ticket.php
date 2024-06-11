<?php

namespace App\Model;

use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Searchable;
use App\Services\QueryString\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ticket extends Model
{
    use HasFactory,
        Filterable,
        Sortable,
        Searchable;

    public const FREE = 822;

    protected $table = 'tickets';

    protected $fillable = [
        'title', 'subtitle', 'status', 'type', 'features', 'public_title',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_tickets')
            ->withPivot('features', 'priority', 'quantity', 'price', 'options', 'public_title', 'seats_visible');
    }
}
