<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'priority', 'status', 'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'hours','author_id', 'creator_id', 'view_tpl', 'view_counter'
    ];

    public function topic()
    {
        return $this->belongsToMany(Topic::class);
    }
}
