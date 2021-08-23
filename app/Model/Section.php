<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

class Section extends Model
{
    use HasFactory;

    protected $table = 'section_titles';

    protected $fillable = [
        'section', 'title', 'description'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'sectionTitles_event', 'event_id', 'section_title_id');
    }

}
